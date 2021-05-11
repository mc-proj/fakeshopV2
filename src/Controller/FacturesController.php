<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\FacturesSP;
use App\Repository\FacturesRepository; 

/**
* @Route("/factures")
*/

class FacturesController extends AbstractController
{
    private $factureRepository;

    public function __construct(
        FacturesRepository $facturesRepository) 
    {
        $this->facturesRepository = $facturesRepository;
    }

    /**
     * @Route("/unefacture", name="recupere_une_facture")
     */
    public function recupereUneFacture(Request $request) {

        $facture_id = $request->request->get("facture_id");
        $facture = $this->facturesRepository->findOneBy(["id" => $facture_id]);

        $response = json_encode($facture);
        $response = new JsonResponse($response);
        return $response;
    }
}
