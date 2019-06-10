<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\TokenRepository;
use App\Security\LoginFormAuthenticator;
use App\Services\TokenSendler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function registration(Request $request,
                                 EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, TokenSendler $tokenSendler)
    {
        $user = new User; //Là je crée mon utilisateur / mon objet user

        $form = $this->createForm(RegistrationType::class, $user); // ici je crée mon formulaire d'enregistrement utilisateur

        if ($form->handleRequest($request)->isSubmitted()&& $form->isValid()){ // si le formulaire est soumis et que tout va bien

            $passwordEncoded = $passwordEncoder->encodePassword($user,$user->getPassword()); //j'encode le password
            $user->setPassword($passwordEncoded); // je set le password
            $user->setRoles(['ROLE_USER']); // je set le rôle

            //je crée un token
            $token = new Token($user); // on est obligé de lui passer un user en parametre sinon ça ne marchera pas

            $manager->persist($token);
            $manager->flush();   // j'enregistre mon utilisateur

            //Ensuite on appelle le service d'envoi d'email
            $tokenSendler->sendToken($user, $token); // il faut lui passer un user et un token


            $this->addFlash(
                'notice',
                'Un email de confirmation vous a été envoyé, veuillez cliquer sur le lien pour confirmer votre compte'
            );

            return $this->redirectToRoute('home');
        }



        return $this-> render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/confirmation/{value}", name="token_validation")
     */

    //on recupere le token grâce au Token Repository et la methode findbyOne
    public function validateToken( Token $token, EntityManagerInterface $manager, GuardAuthenticatorHandler $guardAuthenticatorHandler,
                                  LoginFormAuthenticator $loginFormAuthenticator, Request $request)
    {
        $user = $token->getUser(); // si j'ai un token je récupère l'utilisateur

        //si l'utilisateur est actif je le redirige vers la page d'accueil
        if($user->getEnable()){ //si ça envoie à true
            $this->addFlash(
                'notice',
                "Le token est déjà validé"
            );
            return $this->redirectToRoute('home');
        }

        // Ensuite on vérifie si le token est valide - s'il a moins de 6h --> fonction dans l'entity Token
        if($token ->isValid()) {

            $user->setEnable(true);
            $manager->flush($user);


            //je mets directement mon utilisateur dans la session - je l'authentifie ds la sécurité de symfony à la main
            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator, //ça c'est mon guard à moi
                'main'
            );
        }

        // si le token n'est pas valide (il a plus de 6h),
        // je remove mon user (cascade persist du coté du token) et mon token
        $manager->remove($token);
        $manager->flush();


        $this->addFlash(
            'notice',
            'Le token est expiré. Inscrivez-vous à nouveau'
        );


        return $this->redirectToRoute('registration');
    }

}
