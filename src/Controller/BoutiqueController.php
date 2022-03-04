<?php

namespace App\Controller;
use App\Entity\Images;
use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Entity\Utilisateurs;
use App\Repository\ProduitRepository;
use App\Repository\BoutiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController
{
    /**
     * @Route("/boutique", name="boutique")
     */
    public function index(): Response
    {
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
        ]);
    }

     /**
     * @param BoutiqueRepository $rep
     * @return Reponse
     * @Route("admin/boutique/list", name="boutique_list")
     */
    public function afficher(BoutiqueRepository $rep){
        $boutiques=$rep->findAll();
        return $this->render('boutique/listboutique.html.twig', [
            'tab' => $boutiques,
        ]);
    }

     

    /**
     * @param ProduitRepository $rep
     * @return Reponse
     * @Route("admin/boutique/listProduit/{val}", name="boutique_listProduit")
     */
    public function afficherProduit($val,ProduitRepository $rep){
        $boutiques=$rep->findByBoutique($val);
        return $this->render('boutique/produitboutique.html.twig', [
            'tab' => $boutiques,
        ]);
    }
    /**
     * @Route("admin/boutique/add",name="boutique_add")
     */

    public function add(Request $request): Response
    {
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class,$boutique);
        $form->add('Ajouter',SubmitType::class);
        
 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
    $images = $form->get('images')->getData();
    
    // On boucle sur les images
    foreach($images as $image){
        // On génère un nouveau nom de fichier
        $fichier = md5(uniqid()).'.'.$image->guessExtension();
        
        // On copie le fichier dans le dossier uploads
        $image->move(
            $this->getParameter('images_directory'),
            $fichier
        );
        
        // On crée l'image dans la base de données
        $img = new Images();
        $img->setName($fichier);
        $boutique->addImage($img);
    }
            
            $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($boutique);
             $entityManager->flush();
             return $this->redirectToRoute('produit_add');
        }

        return $this->render('boutique/add.html.twig', [
            'Fbout' => $form->createView(),
            
        ]) ;
    }

    /**
     * @Route("admin/boutique/edit/{id}",name="update_boutique")
     * Method({"GET", "POST"})
     */
    public function update($id, Request $request)
    {
        $boutique = new boutique();
        $boutique = $this->getDoctrine()
            ->getRepository(boutique::class)
            ->find($id);


        $form = $this->createformbuilder($boutique)
        ->add('nomBoutique',TextType::class)
        ->add('descBoutique',TextType::class)
        ->add('adresseBoutique',TextType::class)
        ->add('Commercant', EntityType::class, [
           
            'class' => Utilisateurs::class,
            'choice_label' => 'Email',
           
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
        ->add('images', FileType::class,[
            'label' => false,
            'multiple' => true,
            'mapped' => false,
            'required' => false
        ])
        ->add('Edit',SubmitType::class)

        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             // On récupère les images transmises
    $images = $form->get('images')->getData();
    
    // On boucle sur les images
    foreach($images as $image){
        // On génère un nouveau nom de fichier
        $fichier = md5(uniqid()).'.'.$image->guessExtension();
        
        // On copie le fichier dans le dossier uploads
        $image->move(
            $this->getParameter('images_directory'),
            $fichier
        );
        
        // On crée l'image dans la base de données
        $img = new Images();
        $img->setName($fichier);
        $boutique->addImage($img);
    }
            
          
            $entityManager = $this->getDoctrine()->getManager();
             $entityManager->flush();
             return $this->redirectToRoute('boutique_list');
         }
         return $this->render('boutique/update.html.twig', [
             'Fbout' => $form->createView(),
             'bout' => $boutique,



            ]);
    }

     /**
     *@Route("admin/boutique/delete/{id}", name="boutique_delete")
     */

    public function Supprimer($id,BoutiqueRepository $rep){
        $boutique=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($boutique);
        $em->flush();

        return $this->redirectToRoute('boutique_list');
    }
    

     

}
