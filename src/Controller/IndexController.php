<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Keyword;
use App\Entity\Livres;
use App\Form\LivresType;
use App\Repository\LivresRepository;
use App\Services\imageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(LivresRepository $livresRepository)
    {
        $livres = $livresRepository ->findAll();

//      $livres = $livresRepository->findBy(['etat'=>'Bon état']);


        return $this->render('home/home.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/livre/add", name="add")
     */
    public function add(EntityManagerInterface $manager,Request $request, ImageHandler $handler) //autowiring
    {
        $path=$this->getParameter('kernel.project_dir').'/public/images'; //récupère la racine du projet

        $form = $this->createForm(LivresType::class);
        $form ->handleRequest($request);

        if ($form->IsSubmitted() && $form->isValid()){

            // récupére les valeurs soumises sous forme d'objet Livre - je récupère mon livre
            $livre = $form->getData(); // recupération du formulaire soumis qu'on stocke ds la variable $livre

            //on doit ajouter l'utilisateur courant / l'utilisateur connecté
            $user = $this->getUser();
            $livre->setUser($user);

            // récupère l'image qui se trouve dans le formulaire soumis
            /** @var Image $image */
            $image = $livre->getImage();

            //J'appelle la méthode handler
            // j'envoie dans les param de ma fonction cette image et le chemin
            $image->setPath($path);
            $manager->persist($livre);
            $manager->flush();

            $this->addFlash(
              'notice',
              'Vous avez ajouté un livre');

            return $this ->redirectToRoute('home');
        }
            return $this->render('home/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/livre/edit/{id}", name="edit")
     *
     */
    public function edit(Livres $livre, EntityManagerInterface $manager, Request $request)
    {

        $this->denyAccessUnlessGranted('EDIT', $livre);//pour empecher de recuperer l'id d'un autre livre et de le modifier
        $form = $this->createForm(LivresType::class, $livre);
        $form->handleRequest($request);

        if ($form->IsSubmitted() && $form->isValid()) {
            $path=$this->getParameter('kernel.project_dir').'/public/images'; //récupère la racine du projet

            $image = $form->getData()->getImage();
            $image->setPath($path);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Vous avez modifié');

        return $this->redirectToRoute('show', [
            'id' => $livre->getId(),
        ]);

    }
        return $this->render('home/edit.html.twig',[
            'livres' => $livre,
            'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("livre/delete/{id}", name="delete")
     */
      public function remove (EntityManagerInterface $manager,Livres $livres) {

        $this->denyAccessUnlessGranted('EDIT', $livres); //pour empecher de recuperer l'id d'un autre livre et de l'effacer

        $manager->remove($livres);
        $manager->flush();

        return $this->redirectToRoute('home');
    }


    /**
     * @Route ("/show/{id}", name="show")
     */
        public function show(Livres $livre)
    {
        return $this->render('home/show.html.twig',[
            'livre' => $livre
        ]);
    }






    /**
     * @Route ("delete/keyword/{id}", name="delete_keyword",
     *     methods={"POST"},
     *     condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'")
     */

    public function deleteKeyword(Keyword $keyword, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($keyword);
        $entityManager->flush();

        return new JsonResponse();
    }


}
