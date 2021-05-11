<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Paniers;
use App\Entity\AdressesLivraison;
use App\Entity\PaniersProduits;
use App\Entity\FacturesSP;
use App\Repository\FacturesRepository;
use App\Entity\FacturesProduits;
use App\Entity\CodePromoUsers;
use App\Repository\PaniersRepository;
use App\Repository\ProduitsRepository;
use App\Repository\PaniersProduitsRepository;
use App\Repository\UsersRepository;
use App\Repository\CodesPromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\UsersController;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use DateTime;

use App\Entity\UsersSP;
use DateInterval;
use Stripe\Stripe;
use Symfony\Component\Validator\Constraints\Date;

/**
* @Route("/panier")
*/

class PaniersController extends AbstractController
{
    private $session;
    private $paniersRepository;
    private $produitsRepository;
    private $paniersProduitsRepository;
    private $usersRepository;
    private $codesPromoRepository;
    private $entityManager;
    private UsersController $usersController;

    public function __construct(
        SessionInterface $session,
        ProduitsRepository $produitsRepository,
        PaniersRepository $paniersRepository,
        PaniersProduitsRepository $paniersProduitsRepository,
        CodesPromoRepository $codesPromoRepository,
        UsersRepository $usersRepository,
        EntityManagerInterface $entityManager,
        FacturesRepository $facturesRepository,
        UsersController $usersController) 
    {
        $this->session = $session;
        $this->paniersRepository = $paniersRepository;
        $this->produitsRepository = $produitsRepository;
        $this->paniersProduitsRepository = $paniersProduitsRepository;
        $this->usersRepository = $usersRepository;
        $this->codesPromoRepository = $codesPromoRepository;
        $this->entityManager = $entityManager;
        $this->usersController = $usersController;
        $this->facturesRepository = $facturesRepository;
    }

    /**
     * @Route("/", name="panier")
     */
    public function index(): Response
    {
        $panier = $this->donnePanier();
        $modifications = [];
        //on controlle que les produits dans le panier sont toujours disponibles
        foreach($panier->getPaniersProduits() as $panier_produit) {

            $quantite = $panier_produit->getQuantite();
            $produit = $panier_produit->getProduitsId();
            $infos = [];
            
            //si la quantite en stock est inferieure à la quantite dans le panier
            if($produit->getQuantiteStock() < $quantite) {

                $infos["produit"] = $produit;
                
                //cas ou le produit peux etre commande sans stock
                if($produit->getCommandeSansStock()) {

                    $difference = $quantite - $produit->getQuantiteStock();
                    //quantite livree dans les delais normaux
                    $infos["quantite"] = $produit->getQuantiteStock();
                    //quantite livree avec un delai supplementaire
                    $infos["difference"] = $difference;
                }

                else {

                    if($produit->getQuantiteStock() > 0) {

                        $panier_produit->setQuantite($produit->getQuantiteStock());
                        $this->entityManager->persist($panier_produit);
                        $infos["quantite"] = $produit->getQuantiteStock();
                    }

                    //retrait du panier
                    else {

                        $this->entityManager->remove($panier_produit);
                        $infos["quantite"] = "produit indisponible";
                    }

                    $this->entityManager->flush();
                }

                array_push($modifications, $infos);
            }
        }

        if(count($modifications) > 0) {

            $this->enregistrePanier($panier);
        }

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'modifications' => $modifications,
        ]);
    }

    /**
     * @Route("/modifie-quantite", name="panier_change_quantite", methods={"POST"})
     */
    public function editeQuantite(Request $request) {

        $quantite = $request->request->get("quantite");
        $id_produit = $request->request->get("id_produit");
        $mode = $request->request->get("mode");
        $nombre_articles = false;
        $quantite_finale_produit = 0;

        //cote front, le front est à 0 (et provoque la suppression du panier)
        //si la quantite < 1 à ce niveau, c'est que le user a modifie le front
        if($quantite == '' || $quantite < 1) {

            $is_ok = false;
        }

        else {

            $panier = $this->donnePanier();
            $nouveau = true;
            $nombre_articles = 0;
            $liaison = null;

            foreach($panier->getPaniersProduits() as $panier_produit) {

                $produit = $panier_produit->getProduitsId();

                if($produit->getId() == $id_produit) {

                    if($mode == "edition") {

                        $panier_produit->setQuantite($quantite);
                    }

                    else {

                        $quantite_base = $panier_produit->getQuantite();
                        $panier_produit->setQuantite($quantite_base + $quantite);
                    }

                    //securite: on empeche de mettre plus de produits que ce qui est en stock (sauf cas produit commandable sans stock)
                    if(!$produit->getCommandeSansStock() && ($panier_produit->getQuantite() > $produit->getQuantiteStock())) {

                        $panier_produit->setQuantite($produit->getQuantiteStock());
                    }

                    $this->entityManager->persist($panier_produit);
                    $nouveau = false;
                    $quantite_finale_produit = $panier_produit->getQuantite();
                }

                $nombre_articles += $panier_produit->getQuantite();
            }

            if($nouveau) {

                $produit = $this->produitsRepository->findOneBy(["id" => $id_produit]);

                if($produit != null) {

                    $panier_produit = new PaniersProduits();
                    $panier_produit->setProduitsId($produit);
                    $panier_produit->setPaniersId($panier);

                    //securite: on empeche de mettre plus de produits que ce qui est en stock (sauf cas produit commandable sans stock)
                    if(!$produit->getCommandeSansStock() && ($panier_produit->getQuantite() > $produit->getQuantiteStock())) {

                        $panier_produit->setQuantite($produit->getQuantiteStock());
                        $nombre_articles += $produit->getQuantiteStock();
                    }

                    else {

                        $panier_produit->setQuantite($quantite);
                        $nombre_articles += $quantite;
                    }

                    $this->entityManager->persist($panier_produit);
                    $liaison = $panier_produit;
                    $quantite_finale_produit = $panier_produit->getQuantite();
                }
            }

            $this->entityManager->flush();
            $this->enregistrePanier($panier, $liaison);
        }

        $this->session->set("nombre_articles", $nombre_articles);

        $retour = [
            "quantite_finale_produit" => $quantite_finale_produit,
            "nombre_articles" => $nombre_articles,
            "total_ht" => $panier->getMontantHt(),
            "total_ttc" => $panier->getMontantTtc()
        ];

        $response = json_encode($retour);
        $response = new JsonResponse($response);
        return $response;
    }

    /**
     * @Route("/retrait", name="panier_retrait", methods={"POST"})
     */
    public function retrait(Request $request) {

        $panier = $this->donnePanier();
        $id_produit = $request->request->get("id_produit");
        $quantite_retrait = $request->request->get("quantite");
        $nombre_articles = $this->compteArticlesPanier() - $quantite_retrait;
        $produit = $this->produitsRepository->findOneBy(["id" => $id_produit]);

        $panier_produit = $this->paniersProduitsRepository->findOneBy([
            "paniers_id" => $panier,
            "produits_id" => $produit
        ]);

        if($panier_produit != null && $produit != null) {

            $panier_produit->setPaniersId(null);
            $panier_produit->setProduitsId(null);
            $this->entityManager->remove($panier_produit);
            $this->entityManager->flush();
        }

        $this->enregistrePanier($panier);

        if($nombre_articles == 0) {

            $this->resetPanier();
        }

        //pour maj du nombre dans l'icone panier du header
        $this->session->set("nombre_articles",  $nombre_articles);

        $retour = [
            "nombre_articles" => $nombre_articles,
            "total_ht" => $panier->getMontantHt(),
            "total_ttc" => $panier->getMontantTtc()
        ];

        $response = json_encode($retour);
        $response = new JsonResponse($response);
        return $response;
    }

    /**
     * @Route("/vide", name="panier_vide", methods={"POST"})
     */
    public function videPanier() {

        $this->resetPanier();
        return new JsonResponse();
    }

    public function resetPanier() {

        $panier = $this->donnePanier();

        //secu bonus -- cette fonction est appellee apres validation du paiement
        //theoriquement le panier ne peux pas etre null
        if($panier != null) {

            $now = new dateTime();
            $panier = $this->donnePanier();
            $panier->setCodesPromoId(null);
            $panier->setCommandeTerminee(0);
            $panier->setDateCreation($now);
            $panier->setDateModification($now);
            $panier->setMontantHt(0);
            $panier->setMontantTtc(0);

            //suppression des produits lies (s'il y en a)
            $produits_lies = $panier->getPaniersProduits();

            if($produits_lies != null) {

                foreach($produits_lies as $produit_lie) {

                    $produit_lie->setPaniersId(null);
                    $produit_lie->setProduitsId(null);
                    $this->entityManager->remove($produit_lie);
                }
            }

            //suppression de l'adresse de livraison differente de celle du user (s'il y en a une)
            $adresse_livraison = $panier->getIdAdressesLivraison();

            if($adresse_livraison != null) {

                $adresse_livraison->setActif(false);
                $adresse_livraison->setDerniereModification(new dateTime());
                $panier->setIdAdressesLivraison(null);
                $this->entityManager->persist($adresse_livraison);
            }

            $panier->setMessage(null);
            $this->entityManager->persist($panier);
            $this->entityManager->flush();
            $this->session->set("nombre_articles", 0);
        }        
    }

    public function compteArticlesPanier() {

        $panier = $this->donnePanier();
        $quantite_totale = 0;

        foreach($panier->getPaniersProduits() as $panier_produit) {

            $quantite_totale += $panier_produit->getQuantite();
        }

        $this->session->set("nombre_articles", $quantite_totale);
        return $quantite_totale;
    }

    /**
     * @Route("/paiement", name="panier_paiement")
     */
    public function paiement(Request $request) {

        $user = $this->getUser();
        $panier = $this->donnePanier();
        $cartes = [];
        $methodes_de_paiement = null;
        $this->session->set("stripe_pk", $this->getParameter("stripe_pk"));

        if($user == null) {

            $this->addFlash(
                'acces_flux_paiement',
                "Veuillez vous connecter pour valider votre commande"
            );
            return $this->redirectToRoute('app_login');
        }

        if(sizeof($panier->getPaniersProduits()) == 0) {

            $this->addFlash("panier vide", "Vous ne pouvez pas valider votre commande: votre panier est actuellement vide");
            return $this->redirectToRoute("panier");
        }

        \Stripe\Stripe::setApiKey(
            $this->getParameter("stripe_sk")
        );

        $stripe = new \Stripe\StripeClient(
            $this->getParameter("stripe_sk")
        );

        $customer = null;
        $date = new DateTime();
        $date = $date->format("d.m.y");

        if($user->getIdStripe() != null && $user->getIdStripe() != "") {

            $customer = $stripe->customers->retrieve(
                $user->getIdStripe(),
                []
            );

            $methodes_de_paiement = $stripe->paymentMethods->all([
                'customer' => $user->getIdStripe(),
                'type' => 'card',
            ]);
        }

        else {

            $customer = \Stripe\Customer::create();
        }

        $this->session->set("stripe_customer", $customer);

        $code_promo = $this->codesPromoRepository->findOneBy(["id" => $this->session->get("code_promo_id")]);
        $total = $panier->getMontantTtc();

        if($code_promo != null) {

            if($code_promo->getTypePromo() == "forfaitaire") {

                $reduction = $code_promo->getValeur();
            }
            
            else {

                $reduction = $panier->getMontantTtc() * $code_promo->getValeur()/10000;
            }

            $total = $panier->getMontantTtc() - $reduction;
            $total = intval($total);
        }

        //l'utilisateur a au moins une carte enregistree
        if($methodes_de_paiement != null) {

            try {

                $intent =  $stripe->paymentIntents->create([
                    'amount' => $total,
                    'currency' => 'eur',
                    'customer' => $customer->id,
                    'description' => 'ACHAT CB FAKE SHOP V2 ' . $date,
                    'receipt_email' => $this->getUser()->getEmail(),
                    'payment_method' => $methodes_de_paiement["data"][0]->id,
                ]);

                foreach($methodes_de_paiement as $moyen) {

                    $carte = [];
                    $carte["methode_id"] = $moyen->id;
                    $carte["brand"] = $moyen->card["brand"];
                    $carte["last4"] = $moyen->card["last4"];
                    $carte["exp_month"] = $moyen->card["exp_month"];
                    $carte["exp_year"] = $moyen->card["exp_year"];
                    array_push($cartes, $carte);
                }
            }

            catch (\Stripe\Exception\CardException $e) {

                // Error code will be authentication_required if authentication is needed
                echo 'Error code is:' . $e->getError()->code;
                $payment_intent_id = $e->getError()->payment_intent->id;
                $intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
            }
        }

        //l'utilisateur n'a aucune carte enregistree
        else {
            
            $intent =  $stripe->paymentIntents->create([
                'amount' => $panier->getMontantTtc(),
                'currency' => 'eur',
                'customer' => $customer->id,
                'payment_method_types' => ['card'],
                'description' => 'ACHAT CB FAKE SHOP V2 ' . $date,
                'receipt_email' => $this->getUser()->getEmail()
            ]);
        }

        $this->session->set("payment_intent", $intent);
        $this->session->set("cartes", $cartes);

        $form_livraison = $this->createFormBuilder()
            ->add("nom", TextType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Nom",
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("prenom", TextType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Prenom"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 3,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("adresse", TextareaType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Adresse",
                    "maxlength" => "255",
                    "rows" => "3",
                    "style" => "resize:none"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("ville", TextType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Ville"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 45,
                    ])
                ]
            ])
            ->add("code_postal", TextType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Code postal"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 4,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("Pays",  CountryType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ"
                ],
                "preferred_choices" => array('FR')
            ])
            ->add("telephone", TextType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Téléphone",
                ],
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez renseigner un numéro de téléphone"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 45,
                    ])
                ],
            ])
            ->add("email", EmailType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Adresse e-mail",
                ],
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez renseigner une adresse mail",
                    ]),
                    new Length([
                        "min" => 6,
                        "max" => 255,
                    ]),
                    new Regex([
                        "pattern" => "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/",
                        "message" => "Adresse email invalide"
                    ])
                ],
            ])
            ->getForm();


        $form_message = $this->construitFormulaireMessage();
        $form_livraison->handleRequest($request);
        $erreurs = [];
        $valeurs_entrees = "";
        $code_promo = $this->codesPromoRepository->findOneBy(["id" => $this->session->get("code_promo_id")]);

        if($form_livraison->isSubmitted()) {

            if($form_livraison->isValid()) {

                $data = $form_livraison->getData();
                $adresse = new AdressesLivraison();
                $adresse->setNom($data["nom"]);
                $adresse->setPrenom($data["prenom"]);
                $adresse->setAdresse($data["adresse"]);
                $adresse->setVille($data["ville"]);
                $adresse->setCodePostal($data["code_postal"]);
                $adresse->setPays($data["Pays"]);
                $adresse->setTelephone($data["telephone"]);
                $adresse->setEmail($data["email"]);
                $adresse->setPaniers($this->donnePanier());
                $adresse->setActif(true);
                $adresse->setDerniereModification(new dateTime());
                $this->entityManager->persist($adresse);
                $this->entityManager->flush();
                $this->session->set("adresse_livraison_custom", $data);
            }

            else {

                foreach($form_livraison->getErrors(true) as $error) {

                    $erreurs[$error->getOrigin()->getName()] = $error->getMessage();
                    $valeurs_entrees = $form_livraison->getData();
                }
            }

            $response = array(
                "erreurs" => $erreurs,
                "valeurs_entrees" => $valeurs_entrees
            );
    
            return new JsonResponse($response);
        }

        return $this->render("panier/paiement.html.twig", [

            'user' => $user,
            "panier" => $panier,
            "code_promo" => $code_promo,
            "form_livraison" => $form_livraison->createView(),
            "form_message" => $form_message->createView(),
            "cartes" => $cartes
        ]);
    }

    public function construitFormulaireMessage() {

        $form_message = $this->createFormBuilder()
            ->setAction($this->generateUrl("panier_soumission_message"))
            ->add("message", TextareaType::class, [
                "label" => "Notes de commande (facultatif)",
                "attr" => [
                    "class" => "champ",
                    "maxlength" => "255",
                    "rows" => "3",
                    "placeholder" => "Commentaires concernant votre commande, ex. : consignes de livraison."
                ],
                "constraints" => [
                    new Length([
                        "max" => 255,
                    ])
                ]
            ])
        ->getForm();

        return $form_message;
    }

    /**
     * @Route("/soumetmessage", name="panier_soumission_message", methods={"POST"})
     */
    public function traiteFormulaireMessage(Request $request) {

        $form_message = $this->construitFormulaireMessage();
        $form_message->handleRequest($request);
        $data = $form_message->getData();
        $response = "ok";

        if($form_message->isSubmitted()) {

            if($form_message->isValid()) {

                $panier = $this->donnePanier();
                $panier->setMessage($data["message"]);
                $this->entityManager->persist($panier);
                $this->entityManager->flush();
            }

            else {

                $response = "nok";
            }
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/paiement_post", name="panier_paiement_post", methods={"POST"})
     */
    public function postPaiement(Request $request) {

        $stripe = new \Stripe\StripeClient(
            $this->getParameter("stripe_sk")  
        );

        \Stripe\Stripe::setApiKey(
            $this->getParameter("stripe_sk")
        );

        $conditions_lues = true;

        if($request->request->get("conditions_lues") == "false") {

            $conditions_lues = false;
        }

        //gere le cas ou l'utilisateur a entré et validé une adresse de livraison differente
        //puis a décoché la case "livrer à une adresse differente" avant de valider le paiement
        if($request->request->get("adresse_differente") == "false") {

            $panier = $this->donnePanier();
            $adresse_custom = $panier->getIdAdressesLivraison();

            if($adresse_custom != null) {

                $adresse_custom->setActif(false);
                $adresse_custom->setDerniereModification(new dateTime());
                $panier->getIdAdressesLivraison(null);
                $this->entityManager->persist($panier);
                $this->entityManager->persist($adresse_custom);
                $this->entityManager->flush();
            } 
        }

        $intent = $this->session->get("payment_intent");

        //utilisation d'une carte enregistree
        if($request->request->get("payment_method_id") != null) {

            try {

                //si le moyen de paiement est different de celui attache par defaut, on le change
                if($intent->payment_method != $request->request->get("payment_method_id")) {

                    $intent->payment_method = $request->request->get("payment_method_id");
                }
            }

            catch(\Stripe\Exception\CardException $e) {

                $payment_intent_id = $e->getError()->payment_intent->id;
                $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
            }
        }

        return new JsonResponse([
            "client_secret" => $intent["client_secret"],
            "conditions_lues" => $conditions_lues
        ]);
    }

    /**
     * @Route("/sauvecarte", name="panier_sauve_carte", methods={"POST"})
     */
    public function sauveCarte() {

        $customer = $this->session->get("stripe_customer");
        $user = $this->getUser();
        $user->setIdStripe($customer->id);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        //a passer dans le constructeur pour eviter le X3
        \Stripe\Stripe::setApiKey(
            $this->getParameter("stripe_sk")
        );

        $stripe = new \Stripe\StripeClient(
            $this->getParameter("stripe_sk")
        );

        return new JsonResponse([
            //
        ]);
    }

    /**
     * @Route("/paiement_success", name="panier_paiement_success", methods={"POST"})
     */
    public function paiementReussi() {

        $now = new dateTime();
        $reduction = 0;
        $user = $this->getUser();
        $panier = $this->donnePanier();
        $montant = $panier->getMontantTtc();

        //cas ou l'utilisateur raffraichi la page juste apres confirmation paiement -- redirection vers l'accueil
        if($montant == 0.0) {

            return $this->redirectToRoute("accueil");
        }

        $code_promo = $this->codesPromoRepository->findOneBy(["id" => $this->session->get("code_promo_id")]);

        if($code_promo != null) {

            $code_promo_user = new CodePromoUsers();
            $code_promo_user->setCodesPromoId($code_promo);
            $code_promo_user->setUsersId($user);
            $code_promo_user->setDateUtilisation($now);
            $this->entityManager->persist($code_promo_user);

            if($code_promo->getTypePromo() == "forfaitaire") {

                $reduction = $code_promo->getValeur();
            }
            
            else {

                $reduction = $panier->getMontantTtc() * $code_promo->getValeur()/(100*100);
            }
        }

        $montant -= $reduction;
        $facture = new FacturesSP();
        $facture->setUsersId($user);
        $facture->setCodesPromoId($code_promo);
        $facture->setDateCreation($now);
        $facture->setMontantTtc($panier->getMontantTtc());
        $facture->setMontantHt($panier->getMontantHt());
        $facture->setMontantTotal($montant);
        $facture->setIdAdresseLivraison($panier->getIdAdressesLivraison());
        $facture->setMessage($panier->getMessage());
        $this->entityManager->persist($facture);
        $produits_lies = [];

        foreach($panier->getPaniersProduits() as $produit_lie) {

            $facture_produit = new FacturesProduits();
            $facture_produit->setFacturesId($facture);
            $facture_produit->setProduitsId($produit_lie->getProduitsId());
            $facture_produit->setQuantite($produit_lie->getQuantite());
            $this->entityManager->persist($facture_produit);
            array_push($produits_lies, $facture_produit);
        }

        //necessaire : il faut supprimer les relations d'un panier avant de l'effacer
        $this->resetPanier();
        $this->entityManager->remove($panier);
        $this->entityManager->flush();
        $facture = $this->facturesRepository->findOneBy(["id" => $facture->getId()]);

        return $this->render('panier/paiement_reussi.html.twig', [

            'facture' => $facture,
            'produits_lies' => $produits_lies
        ]);
    }

    /**
     * @Route("/paiement_fail", name="panier_paiement_fail", methods={"POST"})
     */
    public function paiemenentEchec(Request $request) {

        $erreur = $request->request->get("erreur");

        return $this->render('panier/paiement_echec.html.twig', [
            'erreur' => $erreur,
        ]);
    }

    /**
     * @Route("/apercu_panier", name="panier_apercu", methods={"POST"})
     */
    public function apercu_panier_ajax() {

        $panier = $this->donnePanier();
        $resultats = [];

        foreach($panier->getPaniersProduits() as $panier_produit) {

            $quantite = $panier_produit->getQuantite();
            $produit_id = $panier_produit->getProduitsId();
            $produit = $this->produitsRepository->findOneBy(["id" => $produit_id]);
            $id = $produit->getId();

            $now = new dateTime();
            $tarif = 0;

            if($produit->getDateDebutPromo() <= $now && $produit->getDateFinPromo() >= $now) {

                $tarif = $produit->getTarifPromo();
            }

            else {

                $tarif = $produit->getTarif();
            }

            $tva = $produit->getTauxTvaId()->getTaux();
            $tarif = $tarif + ($tarif * $tva/10000);
            $images = $produit->getImages();
            
            $prod = [
                "id" => $id,
                "quantite" => $quantite,
                "nom" => $produit->getNom(),
                "tarif" => $tarif,
                "image" => $images[0]->getImage()
            ];
            array_push($resultats, $prod);
        }

        $response = json_encode($resultats);
        $response = new JsonResponse($response);
        return $response;
    }

    public function donnePanier() {

        if($this->getUser()) {

            $panier = $this->paniersRepository->findOneBy(["users_id" => $this->getUser()]);

            if($panier == null) {

                $panier = new Paniers();
                $panier->setUsersId($this->getUser());
                $panier->setCommandeTerminee(false);
                $panier->setDateCreation(new DateTime());
                $panier->setDateModification(new DateTime());
                $panier->setMontantHt(0);
                $panier->setMontantTtc(0);
                $this->entityManager->persist($panier);
                $this->entityManager->flush();
            }

            return $panier;
        }

        else {

            if($this->session->get("visiteur_id") != null) {

                $panier = $this->paniersRepository->findOneBy(["users_id" => $this->session->get("visiteur_id")]);

                if($panier == null) {

                    $panier = new Paniers();
                    $user = $this->usersRepository->findOneBy(["id" => $this->session->get("visiteur_id")]);
                    $panier->setUsersId($user);
                    $panier->setCommandeTerminee(false);
                    $panier->setDateCreation(new DateTime());
                    $panier->setDateModification(new DateTime());
                    $panier->setMontantHt(0);
                    $panier->setMontantTtc(0);
                    $this->entityManager->persist($panier);
                    $this->entityManager->flush();
                }

                return $panier;
            }

            else {

                $panier = new Paniers();
                $visiteur = $this->usersController->creeVisiteur();
                $panier->setUsersId($visiteur);
                $panier->setCommandeTerminee(false);
                $panier->setDateCreation(new DateTime());
                $panier->setDateModification(new DateTime());
                $panier->setMontantHt(0);
                $panier->setMontantTtc(0);
                $this->entityManager->persist($panier);
                $this->entityManager->flush();
                return $panier;
            }
        }
    }

    public function enregistrePanier($panier_recu, $panier_produit_recu=null) {

        $montants = $this->calculeMontantsPanier($panier_recu, $panier_produit_recu);

        if($panier_produit_recu == null) {

            $panier_recu->setMontantHt($montants["ht"]);
            $panier_recu->setMontantTtc($montants["ttc"]);
        }

        else {

            $ht = $panier_recu->getMontantHt() + $montants["ht"];
            $ttc = $panier_recu->getMontantTtc() + $montants["ttc"];
            $panier_recu->setMontantHt($ht);
            $panier_recu->setMontantTtc($ttc);
        }

        $panier_recu->setDateModification(new DateTime());
        $this->entityManager->persist($panier_recu);
        $this->entityManager->flush();
    }

    public function panierVisiteurVersIdentifie() {

        if($this->session->get("visiteur_id") != null && $this->getUser()) {

            $panier_visiteur = $this->paniersRepository->findOneBy(["users_id" => $this->session->get("visiteur_id")]); 
            $panier_user = $this->donnePanier(); 
            
            //controle qu'un panier visiteur existe bien et qu'il n'est pas vide (montant == 0)
            if($panier_visiteur != null && $panier_visiteur->getMontantHt() > 0) {
                
                //suppression des anciennes liaisons du panier_user avec les produits
                foreach($panier_user->getPaniersProduits() as $panierProduit) {

                    $panierProduit->setPaniersId(null);
                    $panierProduit->setProduitsId(null);
                    $this->entityManager->remove($panierProduit);
                }

                $this->entityManager->flush();

                //transfert des liaisons du panier_visiteur vers le panier_user
                foreach($panier_visiteur->getPaniersProduits() as $panierProduit) {

                    $panier_user->addPaniersProduit($panierProduit);
                }

                $this->entityManager->remove($panier_visiteur);
                $this->enregistrePanier($panier_user);
            }

            //le visiteur a un panier vide lié, il faut supprimer le panier avant de pouvoir
            //supprimer le visiteur (a cause de leur liaison)
            else if($panier_visiteur != null) {

                $panier_visiteur->setUsersId(null);
                $this->entityManager->remove($panier_visiteur);
            }

            $visiteur_id = $this->session->get("visiteur_id");
            $visiteur = $this->usersRepository->findOneBy(["id" => $visiteur_id]);
            $this->entityManager->remove($visiteur);
            $this->entityManager->flush();
            $this->session->set("visiteur_id", null);
        }
    }

    public function destroyPanier() {

        $panier = $this->donnePanier();

        foreach($panier->getPaniersProduits() as $panierProduit) {

            $panierProduit->setPaniersId(null);
            $panierProduit->setProduitsId(null);
            $this->entityManager->remove($panierProduit);
        }

        $this->entityManager->remove($panier);
        $this->entityManager->flush();
    }

    public function calculeMontantsPanier($panier, $panier_produit_recu=null) {

        $total_ht = 0;
        $total_ttc = 0;
        $montants = [];
        $now = new dateTime();
        $liaisons = [$panier_produit_recu];

        if($liaisons[0] == null) {

            $liaisons = $panier->getPaniersProduits();
        }

        foreach($liaisons as $panier_produit) {

            if($panier_produit->getId() != null) {

                $produit = $panier_produit->getProduitsId();
                $tva = $produit->getTauxTvaId()->getTaux();
                $quantite = $panier_produit->getQuantite();

                if($produit->getDateDebutPromo() <= $now && $produit->getDateFinPromo() >= $now) {

                    $total_ht += ($produit->getTarifPromo() * $quantite);
                }

                else {

                    $total_ht += ($produit->getTarif() * $quantite);
                }

                $total_ttc = $total_ht + ($total_ht * $tva/10000);
            }               
        }

        $montants["ht"] = $total_ht;
        $montants["ttc"] = $total_ttc;
        return $montants;
    }
}