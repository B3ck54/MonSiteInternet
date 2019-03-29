<?php

namespace App\Controller;

use App\Form\SearchLivreType;
use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/livre/search", name="search_livre")
     */
    public function searchCar(Request $request, LivresRepository $livresRepository)
    {
        $livres = [];
        $searchLivreForm = $this->createForm(SearchLivreType::class);

        //on récupère le formulaire dans le contrôleur
        if($searchLivreForm->handleRequest($request)->isSubmitted() && $searchLivreForm->isValid()){
            $critere = $searchLivreForm->getData(); // je récupère le résultat de mon formulaire dans la variable $critere -> je la passe dans le LivreRepository
            $livres = $livresRepository->searchLivre($critere);

//            dd($livres);

        }

        return $this->render('search/livre.html.twig', [
            'search_form' => $searchLivreForm->createView(),
            'livres' => $livres
        ]);
    }
}
