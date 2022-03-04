<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
     * @Route("admin/produit", name="produit")
     */
    public function index(): Response
    {
        return $this->render('dashboard/produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    /**
     * @param ProduitRepository $rep
     * @return Reponse
     * @Route("admin/produit/list", name="produit_list")
     */
    public function afficher(ProduitRepository $rep){
        $produits=$rep->findAll();
        return $this->render('dashboard/produit/listproduit.html.twig', [
            'tab' => $produits,
        ]);
    }

       
    
     /**
     * @Route("admin/produit/add",name="produit_add")
     */

    public function add(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class,$produit);
        $form->add('Ajouter',SubmitType::class);
        
 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $produit->setImage($filename);
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($produit);
             $entityManager->flush();
             return $this->redirectToRoute('boutique_listProduit' ,[ 'val' =>$produit->getBoutique()->getId() ]);
        }

        return $this->render('dashboard/produit/add.html.twig', [
            'Fprod' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("admin/produit/edit/{id}",name="update_produit")
     * Method({"GET", "POST"})
     */
    public function update($id, Request $request)
    {
        $produit = new produit();
        $produit = $this->getDoctrine()
            ->getRepository(produit::class)
            ->find($id);


        $form = $this->createformbuilder($produit)
        ->add('nomProduit',TextType::class)
        ->add('prixProduit',TextType::class)
        ->add('quantiteProduit',TextType::class)
            ->add('descProduit',TextType::class)
            ->add('image',FileType::class,[
                'mapped'=> false,
                'label'=>' Telecharger une imagee'

            ])
            ->add('Edit',SubmitType::class)

        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $produit->setImage($filename);
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->flush();
             return $this->redirectToRoute('boutique_listProduit' ,[ 'val' =>$produit->getBoutique()->getId() ]);
         }
         return $this->render('dashboard/produit/update.html.twig', [
             'Fprod' => $form->createView(),
             'prod' => $produit,



            ]);
    }

     /**
     *@Route("admin/produit/delete/{id}", name="produit_delete")
     */

    public function Supprimer($id,ProduitRepository $rep){
        $produit=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('produit_list');
    }
}
