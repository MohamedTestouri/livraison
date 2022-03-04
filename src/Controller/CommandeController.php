<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\Utilisateur;
use App\Entity\LigneCommande;
use App\Services\cart\CartService;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\LigneCommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CommandeController extends AbstractController
{
     /**
     * @Route("/commande", name="commande")
     * @IsGranted("ROLE_USER")
     */
    public function ajoutercommande(CartService $cartService,CommandeRepository $repCommande,Request $request,SessionInterface $session, ProduitRepository $produitrep): Response
    {   $panier = $session->get("panier",[]);
        $pan2 = $panier;

        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();
        
        $commande = new Commande();
    
        $form = $this->createForm(CommandeType::class,$commande);
        $form->add('Ajouter',SubmitType::class) ; 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()){
            
            $commande = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

           

            foreach ($pan2 as $id => $quantite) {
               
                $ligneCommande = new ligneCommande();
                $prod = $produitrep->find($id);
                $ligneCommande = $ligneCommande->setProduit($prod);
                $ligneCommande = $ligneCommande->setQuantite($quantite);
                
               
                $commande = $repCommande->findOneBy([], ['id' => 'desc']);
                $lastId = $commande->getId(); 
                $test = $repCommande->find($lastId);
                $ligneCommande = $ligneCommande->setCommande($test);

                $em = $this->getDoctrine()->getManager();
                $em->persist($ligneCommande);
                $em->flush();


                }

             return $this->redirectToRoute('home');
        }
       


        return $this->render('commande/ajouter.html.twig', [
                    'formA' => $form->createView(),
                    'total' => $total,
                    'elements' => $dataPanier
        ]);    
    }

    /**
     * @Route("/admin/affcommande", name="admincommande")
     */

     function afficher(CommandeRepository $rep,LigneCommandeRepository $ligneCommande)
    {
         $commande = $rep->findall();
         $ligneCommande = $ligneCommande->findall();
         return $this->render('dashboard/commande/index.html.twig', [
             'tab' => $commande,
             'tab1' => $ligneCommande

         ]);

    }

     /**
     * @Route("/admin/suppcommande/{id}", name="adminsupp")
     */

    function supprimercommande($id ,CommandeRepository $rep)
    {
         $commande = $rep->find($id);
         $em=$this->getDoctrine()->getManager(); 
         $em->remove($commande);
         $em->flush(); 

     return $this->redirectToRoute('admincommande');

    }


     /**
     * @Route("/admin/modifcommande/{id}", name="adminmodif")
     */

    function modifiercommande($id ,CommandeRepository $rep, Request $request)
    {
        $commande=$rep->find($id);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('admincommande');
       }

       return $this->render('dashboard/commande/modifier.html.twig', [
        'form' => $form->createView(),
    ]);
    }

}