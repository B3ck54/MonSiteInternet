<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */

    //@Security("is_granted('ROLE_ADMIN')") ou voir dans le security.yaml

    public function admin(UserRepository $userRepository)
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(), //on va afficher tous les users
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
}
