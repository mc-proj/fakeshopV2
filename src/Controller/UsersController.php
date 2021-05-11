<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\UsersSP;
use App\Entity\FacturesSP;
use App\Repository\FacturesRepository;  
use App\Form\UsersType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotIdenticalTo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use DateTime;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UsersController extends AbstractController
{
    private $session;
    private $entityManager;
    private $usersRepository;
    private $factureRepository;
    private $mailer;
    private $passwordEncoder;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository,
        FacturesRepository $facturesRepository,
        MailerInterface $mailer,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->usersRepository = $usersRepository;
        $this->facturesRepository = $facturesRepository;
        $this->mailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/inscription", name="page_inscription")
     */
    public function index(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add("pseudo", TextType::class, [
                "label" => "Nom d'utilisateur (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ]),

                    //les objets Users avec le pseudo "user" sont des objets crees automatiquement
                    //pour stocker le panier des utilisateurs non identifies (voir fonction creeVisiteur() de ce controller)
                    //est une secu en plus, vu que le pseudo a une contrainte de 5 caracteres minimum
                    new NotIdenticalTo([
                        "value" => "user"
                    ])
                ]
            ])
            ->add("nom", TextType::class, [
                "label" => "Nom (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
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
                "label" => "Prénom (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
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
            ->add("adresse", TextType::class, [
                "label" => "Adresse (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
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
                "label" => "Ville (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
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
            ->add("code_postal", TextType::class, [
                "label" => "Code postal (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
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
                "label" => "Pays (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "preferred_choices" => array('FR')
            ])
            ->add("telephone", TextType::class, [
                "label" => "Téléphone (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez renseigner un numéro de téléphone"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ])
                ],
            ])
            ->add("email", RepeatedType::class, [
                "type" => EmailType::class,
                "mapped" => true,
                "first_options"  => ["label" => "Adresse email (champ obligatoire)", "attr" => ["class" => "champ"]],
                "second_options" => ["label" => "Confirmer adresse email (champ obligatoire)", "attr" => ["class" => "champ"]],
                "invalid_message" => "Les adresses email doivent concorder",
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
            ->add("plainPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "mapped" => false,
                "first_options"  => [
                    "label" => "Mot de passe (champ obligatoire)",
                    "attr" => ["class" => "champ"]
                ],
                "second_options" => [
                    "label" => "Confirmer mot de passe (champ obligatoire)",
                    "attr" => ["class" => "champ"],
                ],
                "invalid_message" => "Les mots de passe doivent concorder",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez renseigner un mot de passe",
                    ]),
                    new Length([
                        "max" => 255,
                    ]),
                    new Regex([
                        "pattern" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{6,}$/",
                        "message" => "Attention, votre mot de passe doit comprendre au minimum 6 caractères, une majuscule, une minuscule, un chiffre et un caractère parmi : ! @ # $ % ^ & * _ = + - . "
                    ]),
                ],
            ])
            ->add('captcha', CaptchaType::class, array(
                "label" => "Captcha :",
                "attr" => [
                    "class" => "champ"
                ]
            ))
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label_format' => 'J\'accepte les conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez acceptez les conditions.',
                    ]),
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $mail = $form->get('email')->getData();
            $mail = serialize($mail);
            $mail = base64_encode($mail);
            $user = $this->usersRepository->findOneBy(["email" => $mail]);

            if($user == null) {

                $user = new UsersSP();
                $data = $form->getData();
                $user->setPseudo($data["pseudo"]);
                $user->setNom($data["nom"]);
                $user->setPrenom($data["prenom"]);
                $user->setAdresse($data["adresse"]);
                $user->setCodePostal($data["code_postal"]);
                $user->setVille($data["ville"]);
                $user->setPays($data["Pays"]);
                $user->setTelephone($data["telephone"]);
                $user->setEmail($form->get('email')->getData());

                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setRoles(['ROLE_USER']);
                $user->setDateCreation(new DateTime());
                $user->setDateModification(new DateTime());
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                /*$email = (new TemplatedEmail())
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

                $mailer->send($email);*/

                $this->addFlash(
                    'confirmation_compte_cree',
                    'Votre Compte a bien été crée. Vous pouvez à présent vous connecter.'
                );

                return $this->redirectToRoute('app_login');
            }

            else {

                $this->addFlash(
                    'erreur_creation_compte',
                    'Erreur: cette adresse email est deja utilisée'
                );
            }
        }

        //si le formulaire soumis comporte une/des erreur(s)
        else if($form->isSubmitted() && $form->getErrors(true)) {

            $this->addFlash(
                'erreur_creation_compte',
                'Erreur: un des champs fourni est incorrect'
            );
        }

        return $this->render('users/inscription.html.twig', [

            'formulaire_inscription' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte", name="page_compte")
     */
    public function monCompte(Request $request) {

        //securite bonus - security.yaml controlle deja
        if($this->getUser()) {

            $user = $this->getUser();
            $form_profil = $this->createForm(UsersType::class, $user);

            $form_mdp = $this->createFormBuilder()
                ->add("plainPassword", RepeatedType::class, [
                    "type" => PasswordType::class,
                    "mapped" => false,
                    "first_options"  => [
                        "label" => "Mot de passe (champ obligatoire)",
                        "attr" => ["class" => "champ"]
                    ],
                    "second_options" => [
                        "label" => "Confirmer mot de passe (champ obligatoire)",
                        "attr" => ["class" => "champ"],
                    ],
                    "invalid_message" => "Les mots de passe doivent concorder",
                    "attr" => [
                        "class" => "champ"
                    ],
                    "constraints" => [
                        new NotBlank([
                            "message" => "Veuillez renseigner un mot de passe",
                        ]),
                        new Length([
                            "max" => 255,
                        ]),
                        new Regex([
                            "pattern" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{6,}$/",
                            "message" => "Attention, votre mot de passe doit comprendre au minimum 6 caractères, une majuscule, une minuscule, un chiffre et un caractère parmi : ! @ # $ % ^ & * _ = + - . "
                        ]),
                    ],
                ])
                ->getForm();

            $form_profil->handleRequest($request);
            $form_mdp->handleRequest($request);

            //un des formulaires soumis
            if($form_profil->isSubmitted() || $form_mdp->isSubmitted()) {

                $user = $this->getUser();

                if($form_profil->isSubmitted() && $form_profil->isValid()) {

                    $data = $form_profil->getData();
                    $user->setPseudo($data->getPseudo());
                    $user->setNom($data->getNom());
                    $user->setPrenom($data->getPrenom());
                    $user->setAdresse($data->getAdresse());
                    $user->setCodePostal($data->getCodePostal());
                    $user->setVille($data->getVille());
                    $user->setPays($data->getPays());
                    $user->setTelephone($data->getTelephone());
                    $user->setEmail($form_profil->get('email')->getData());
                }
        
                else if($form_mdp->isSubmitted() && $form_mdp->isValid()) {
        
                    $user->setPassword(
                        $this->passwordEncoder->encodePassword(
                            $user,
                            $form_mdp->get('plainPassword')->getData()
                        )
                    );
                }

                $user->setDateModification(new DateTime());
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->redirectToRoute('page_compte');
            }

            $factures = $this->facturesRepository->recupereMinimumInfos($user->getId());

            return $this->render('users/compte.html.twig', [

                "formulaire_profil" => $form_profil->createView(),
                "formulaire_mdp" => $form_mdp->createView(),
                "factures" => $factures,
            ]);
        }

        else {

            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()) {

            return $this->redirectToRoute('page_compte');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout()
    {
        $this->session->set("visiteur_id", null);
        $this->session->set("nombre_articles", null);
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    public function creeVisiteur() {

        $visiteur = new UsersSP();
        $visiteur->setNom("");
        $visiteur->setPrenom("");
        $visiteur->setAdresse("");
        $visiteur->setCodePostal("");
        $visiteur->setVille("");
        $visiteur->setTelephone("");
        $visiteur->setEmail("");
        $visiteur->setPassword("");
        $visiteur->setRoles(['ROLE_USER']);
        $visiteur->setDateCreation(new DateTime());
        $visiteur->setDateModification(new DateTime());
        $visiteur->setPseudo("user");
        $visiteur->setPays("");
        $this->entityManager->persist($visiteur);
        $this->entityManager->flush();
        $this->session->set("visiteur_id", $visiteur->getId());
        return $visiteur;
    }
}
