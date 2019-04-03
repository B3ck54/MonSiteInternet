<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'livres' => $this->getUser()->getLivre(), //montrer les livres de l'utilisateur connecté
        ]);
    }
}
