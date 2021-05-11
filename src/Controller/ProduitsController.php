<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository;
use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
* @Route("/boutique")
*/

class ProduitsController extends AbstractController
{
    private $categoriesRepository;
    private $produitsRepository;
    private $avisRepository;
    private $entityManager;

    public function __construct(CategoriesRepository $categoriesRepository,
                                ProduitsRepository $produitsRepository,
                                EntityManagerInterface $entityManager) {

        $this->categoriesRepository = $categoriesRepository;
        $this->produitsRepository = $produitsRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="categories")
     */
    public function index(): Response
    {
        $categories = $this->categoriesRepository->findAll();

        return $this->render('produits/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
    * @Route("/more", name="plus_de_produits", methods={"POST"})
    */
    public function autresProduits(Request $request) {
    
        $categorie = $request->request->get("categorie");
        $sous_categorie = $request->request->get("sous_categorie");
        $page_visee = $request->request->get("numero_page");
        $tri = $request->request->get("tri");
        $rang_min = ($page_visee - 1) * 16;
        $quantite_max = 16;

        if($sous_categorie == '') {

            $produits = $this->produitsRepository->getByCategorie($categorie, $quantite_max, $rang_min, $tri);
        }

        else {

            $produits = $this->produitsRepository->getBySousCategorie($categorie, $sous_categorie, $quantite_max, $rang_min, $tri);
        }
        
        $produits = json_encode($produits);
        $response = new JsonResponse($produits);
        return $response;
    }

    /**
    * @Route("/recherche", name="recherche_standard")
    */
    public function rechercheStandard(Request $request) {

        $form = $this->createFormBuilder()
            ->add("recherche", TextType::class, [
                "label" => " ",
                "attr" => [
                    "class" => "champ",
                    "placeholder" => "Rechercher..."
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                ]
            ])
            ->setAction($this->generateUrl('recherche_standard'))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $produits = $this->produitsRepository->rechercheGenerale($data["recherche"]);

            return $this->render('produits/resultat_recherche.html.twig', [
                'recherche' => $data["recherche"],
                'produits' => $produits
            ]); 

        }

        return $this->render('produits/recherche.html.twig', [
            'form_recherche' => $form->createView()
        ]);
    }

    public function construitFormulaireAvis() {

        $form_avis = $this->createFormBuilder()
            ->add("avis", TextareaType::class, [
                "label" => "Avis:",
                "attr" => [
                    "maxlength" => "255",
                    "rows" => "3",
                    "style" => "resize:none"
                ]
            ])
            ->add("note", ChoiceType::class, [
                "label" => "Note:",
                "choices" => [
                    "0/5" => 0,
                    "1/5" => 1,
                    "2/5" => 2,
                    "3/5" => 3,
                    "4/5" => 4,
                    "5/5" => 5,
                ]
            ])
            ->add("id_produit", HiddenType::class, [
                //
            ])
            ->setAction($this->generateUrl('post_avis'))
            ->getForm();

        return $form_avis;
    }

    /**
    * @Route("/soumetavis", name="post_avis", methods={"POST"})
    */
    public function soumetFormulaireAvis(Request $request) {

        $form_avis = $this->construitFormulaireAvis();
        $form_avis->handleRequest($request);
        $data = $form_avis->getData();

        if($form_avis->isSubmitted() && $form_avis->isValid()) {

            if($data["id_produit"] != null) {

                $avis = new Avis();
                $avis->setNote($data["note"]);
                $avis->setCommentaire($data["avis"]);
                $avis->setUsersId($this->getUser());
                $produit = $this->produitsRepository->findOneBy(["id" => $data["id_produit"]]);
                $avis->setProduitsId($produit);
                $this->entityManager->persist($avis);
                $this->entityManager->flush();
                $this->note_update($produit);

                $this->addFlash(
                    "confirmation_avis_poste",
                    "Votre avis a bien été enregistré. Merci de l'avoir partagé"
                );
            }

            else {

                $this->addFlash(
                    "erreur_avis_poste",
                    "Une erreur est survenue lors de l'enregistrement de votre avis.
                    Veuillez réessayer ou nous contacter pour signaler ce problème"
                );
            }
        }

        //return back - renvoie vers la route "produits_par_categorie"
        $referer = $request->headers->get("referer");
        return $this->redirect($referer);
    }

    /**
    * @Route("/{categorie}/{sous_categorie}/{produit}", name="produits_par_categorie")
    */
    public function filtreCategorie($categorie, $sous_categorie=null, $produit=null): Response
    {
        $form_avis = $this->construitFormulaireAvis();

        if($produit == null) {

            if($sous_categorie != null) {

                $quantite_totale = $this->produitsRepository->getQuantity($sous_categorie);
            }

            else {

                $quantite_totale = $this->produitsRepository->getQuantity($categorie);
            }
            
            $quantite_totale = $quantite_totale[0]["quantite"];
            $quantite_max = 16;

            if($sous_categorie == null) {

                $produits = $this->produitsRepository->getByCategorie($categorie, $quantite_max, 0);
            }

            else {

                $produits = $this->produitsRepository->getBySousCategorie($categorie, $sous_categorie, $quantite_max, 0);
            }

            return $this->render('produits/categorie.html.twig', [
                'categorie' => $categorie,
                'sous_categorie' => $sous_categorie,
                'produits' => $produits,
                'quantite_totale' => $quantite_totale,
            ]);
        }

        else {

            $produit = $this->produitsRepository->getOneByName($categorie, $sous_categorie, $produit);

            return $this->render('produits/detail.html.twig', [
                'categorie' => $categorie,
                'sous_categorie' => $sous_categorie,
                'produit' => $produit,
                'form_avis' => $form_avis->createView()
            ]);
        }
    }

    private function note_update($id_produit) {

        $produit = $this->produitsRepository->findOneBy(["id" => $id_produit]);
        $notes = [];
        $avis = $produit->getAvis();

        foreach($avis as $opinion) {
            array_push($notes, $opinion->getNote());
        }

        $moyenne = array_sum($notes)/sizeof($notes);
        $produit->setNoteMoyenne($moyenne);
        $this->entityManager->persist($produit);
        $this->entityManager->flush();
    }
}
