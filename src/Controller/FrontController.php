<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Services\cart\CartService;
use App\Repository\ProduitRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\EvenementRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(CartService $cartService,ProduitRepository $rep, BoutiqueRepository $repp): Response
    {
        
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $produits=$rep->findAll();
        $boutiques=$repp->findAll();
       

        return $this->render('front/home.html.twig', [
            'elements' => $dataPanier,
            'total' => $total,
            'controller_name' => 'FrontController',
            'tabprod' => $produits,
            'tabbout' => $boutiques
        ]);
    }
    
    
     /**
     * @Route("/delete1/{id}", name="delete1")
     */
    public function delete1(Produit $produit, SessionInterface $session)
    {
       // On récupère le panier actuel
    $panier = $session->get("panier", []);
    $id= $produit->getId();

    if(!empty($panier[$id])){
      
        unset($panier[$id]);
    }
    
    // on sauvgarde dans la session
    $session->set("panier", $panier);
    
    return $this->redirecttoRoute("home");
    }

     /**
     * @Route("/eventfront", name="ev_front_aff")
     */

    function afficherevenement(EvenementRepository $rep,CartService $cartService)
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

         $evenement = $rep->findall();
         return $this->render('blog/evenementsfront.html.twig', [
             'tab' => $evenement,
             'elements' => $dataPanier,
              'total' => $total
         ]);

    }

     
     /**
     * @Route("/details/{id}", name="details")
     */
    public function details($id,ProduitRepository $rep, BoutiqueRepository $repp,CartService $cartService)
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $produits=$rep->findByBoutique($id);
        $boutiques=$repp->find($id);
        return $this->render('front/details.html.twig', [
             'prod' => $produits,
            'bout' => $boutiques,
            'total' => $total,
            'elements' => $dataPanier
           
        ]);
    }


    
    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail($id,ProduitRepository $rep,BoutiqueRepository $repp,CartService $cartService)
    {
        $produits=$rep->find($id);
        $boutiques=$repp->find($id);

        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        return $this->render('front/detail.html.twig', [
            
            'prod' => $produits,
            'bout' => $boutiques,
            'total' => $total,
            'elements' => $dataPanier

        ]);
    }


 
   

   

    
}
