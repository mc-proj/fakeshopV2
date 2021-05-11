<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PaniersRepository;
use App\Repository\CodesPromoRepository;
use App\Repository\CodePromoUsersRepository;
use DateTime;

class CodesPromoController extends AbstractController
{
    private $session;
    private $paniersRepository;
    private $codesPromoRepository;
    private $codesPromoUsersRepository;

    public function __construct(
        SessionInterface $session,
        PaniersRepository $paniersRepository,
        CodesPromoRepository $codesPromoRepository,
        CodePromoUsersRepository $codePromoUsersRepository
        )
    {
        $this->session = $session;
        $this->paniersRepository = $paniersRepository;
        $this->codesPromoRepository = $codesPromoRepository;
        $this->codePromoUsersRepository = $codePromoUsersRepository;
    }
    
    /**
     * @Route("/promo/recupere", name="recupere_promo", methods={"POST"})
     */
    public function index(Request $request)
    {
        $erreur = "";
        $reduction = 0;
        $description_promo = "";
        $code = $request->request->get("code");
        $code_promo = $this->codesPromoRepository->findOneBy(["code" => $code]);
        $this->session->set("code_promo", null);

        if($code_promo == null) {

            $erreur = "Erreur: ce code promo n'existe pas";
        }

        else {

            $now = new Datetime();

            if(($code_promo->getDateDebutValidite() > $now) || ($code_promo->getDateFinValidite() < $now)) {

                $erreur = "Erreur: ce code promo n'est plus valide";
            }

            else {

                if($this->getUser()) {

                    $user_id = $this->getUser()->getId();
                }
        
                else {
        
                    $user_id = $this->session->get("visiteur_id");
                }

                $code_promo_users = $this->codePromoUsersRepository->findOneBy(["codes_promo_id" => $code_promo, "users_id" => $user_id]);

                if($code_promo_users != null) {

                    $date_utilisation = $code_promo_users->getDateUtilisation();
                    $erreur = "Erreur: vous avez deja utilisé ce code promo le " . $date_utilisation->format('d/m/Y');
                }

                else {

                    $panier = $this->paniersRepository->findOneBy(["users_id" => $user_id]);
                    $prix_panier = $panier->getMontantTtc();

                    if($prix_panier < $code_promo->getMinimumAchat()) {

                        $minimum = number_format($code_promo->getMinimumAchat()/100);
                        $erreur = "Erreur: le minimum d'achat pour ce code promo est de " . $minimum . "€";
                    }

                    else {

                        $description_promo = $code_promo->getDescription();
                        //valeurs attendues pour getTypePromo : "forfaitaire", "proportionnelle"

                        $this->session->set("code_promo_id", $code_promo->getId());

                        if($code_promo->getTypePromo() == "forfaitaire") {

                            $reduction = $code_promo->getValeur();
                        }

                        else {

                            $reduction = $panier->getMontantTtc()/100 * $code_promo->getValeur()/100;
                        }

                        $promo = [
                            "code" => $code,
                            "montant" => $reduction
                        ];
                    }
                }        
            }         
        }

        $retour = [
            "erreur" => $erreur,
            "reduction" => $reduction,
            "description" => $description_promo
        ];   

        $response = json_encode($retour);
        $response = new JsonResponse($response);
        return $response;
    }

    /**
     * @Route("/promo/reset", name="reset_promo", methods={"POST"})
     */
    public function resetPromo() {

        $this->session->set("code_promo_id", null);
        return new JsonResponse();
    }
}
