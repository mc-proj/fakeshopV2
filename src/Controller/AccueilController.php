<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
* @Route("/")
*/

class AccueilController extends AbstractController
{
    /**
    * @Route("/home", name="accueil")
    */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();

        return $this->render('base.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
    * @Route("/recherche", name="recherche_produits", methods = {"POST"})
    */
    public function rechercheProduits(Request $request, ProduitsRepository $produitsRepository) {

        // $beg = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now'));
        $debut = trim($request->request->get("debut"));

        if($debut == '') {

            $produits = [];
        }

        else {

            $produits = $produitsRepository->findByBegin($debut);
        }

        // $end = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now'));
        // dd($end - $beg);


        $produits = json_encode($produits);
        $response = new JsonResponse($produits);
        return $response;
    }
}
