<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Livres;
use App\Repository\UserRepository;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */

    //@Security("is_granted('ROLE_ADMIN')") ou voir dans le security.yaml

    public function admin(UserRepository $userRepository, LivresRepository $livresRepository)
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(), //on va afficher tous les users
            'livres' => $livresRepository->findAll(), //on va afficher tous les livres

        ]);
    }

    /**
     * @Route("/admin/delete_user/{id}", name="delete_user")
     *
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Utilisateur supprimé');

        return $this->render('admin/index.html.twig',[
            'users' => $userRepository->findAll(),
        ]);
    }


    /**
     * @Route("/admin/delete_livre/{id}", name="delete_livre")
     *
     */
    public function deleteBook(Livres $livre, EntityManagerInterface $entityManager, LivresRepository $livresRepository)
    {
        $entityManager->remove($livre);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'livre supprimé');

        return $this->render('admin/index.html.twig',[
            'livres' => $livresRepository->findAll(),
        ]);
    }
}
