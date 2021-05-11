<?php

namespace App\Controller;
use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
* @Route("/")
*/

class AccueilController extends AbstractController
{
    private $produitsRepository;
    private $categoriesRepository;
    private $session;

    public function __construct(ProduitsRepository $produitsRepository,
                                CategoriesRepository $categoriesRepository,
                                SessionInterface $session) {

        $this->produitsRepository = $produitsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->session = $session;
    }

    /**
    * @Route("/", name="accueil")
    */
    public function index(): Response
    {
        $produits_recents = $this->produitsRepository->getLatest(6);
        $meilleurs_produits = $this->produitsRepository->getBest(6);

        return $this->render('accueil/index.html.twig', [

            'produits_recents' => $produits_recents,
            'meilleurs_produits' => $meilleurs_produits
        ]);
    }

    /**
    * @Route("/home/lateral", name="menu_lateral")
    */
    public function menuLateral(): Response
    {
        $categories = $this->categoriesRepository->findAll();

        return $this->render('accueil/menu_lateral.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
    * @Route("/home/navbar", name="menu_navbar")
    */
    public function menuNavbar(): Response
    {
        $categories = $this->categoriesRepository->findAll();

        return $this->render('accueil/menu_navbar.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
    * @Route("/cookies_acceptes", name="cookies_acceptes", methods = {"POST"})
    */
    public function accepteCookies() {

        $this->session->set("cookies_acceptes", true);
        $response = new JsonResponse("");
        return $response;
    }

    /**
    * @Route("/recherche", name="recherche_produits", methods = {"POST"})
    */
    //recherche dynamique pour le header
    public function rechercheProduits(Request $request) {

        $debut = htmlspecialchars(trim($request->request->get("debut")), ENT_QUOTES, "UTF-8");

        if($debut == '') {

            $resultats = [];
        }

        else {

            $resultats = $this->produitsRepository->findProduit($debut);

            if($resultats == []) {

                $resultats = $this->produitsRepository->findProduit($debut);
            }
        }

        $resultats = json_encode($resultats);
        $response = new JsonResponse($resultats);
        return $response;
    }

    /**
    * @Route("/home/cgv", name="page_cgv")
    */
    public function cgv(): Response
    {
        return $this->render('accueil/cgu.html.twig');
    }

    /**
    * @Route("/home/confidentialite", name="page_confidentialite")
    */
    public function confidentialite(): Response
    {
        return $this->render('accueil/confidentialite.html.twig');
    }

    /**
    * @Route("/home/contact", name="contact")
    */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createFormBuilder()
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("email", EmailType::class, [
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez renseigner une adresse email"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ])
                ],
                "label" => "email (champ obligatoire)"
            ])
            ->add("message", TextareaType::class, [
                "constraints" => [
                    new NotBlank([
                        "message" => "Ce champ est obligatoire"
                    ]),
                    new Length([
                        "min" => 3,
                        "max" => 255,
                    ])
                ],
                "label" => "message (champ obligatoire)"
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $email = (new TemplatedEmail())
                ->from($data["email"])
                ->to($this->getParameter('admin_mail'))
                ->subject("Fake Shop V2: un client vous a envoyé un message")
                ->priority(Email::PRIORITY_HIGH)
                ->htmlTemplate("email/contact.html.twig")
                    ->context([
                        "prenom" => $data["prenom"],
                        "nom" => $data["nom"],
                        "message" => $data["message"]
                ]);

            $mailer->send($email);

            $this->addFlash(
                'confirmation_contact',
                'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais'
            );

            return $this->redirectToRoute('contact');
        }

        return $this->render('accueil/contact.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
