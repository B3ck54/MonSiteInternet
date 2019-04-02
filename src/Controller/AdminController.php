<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */

    //@Security("is_granted('ROLE_ADMIN')") ou voir dans le security.yaml

    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}
