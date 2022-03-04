<?php

namespace App\Controller;


use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Services\cart\CartService;
use App\Repository\ReponseRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }


    /**
     * @Route("/reclamation/add/{id}", name="r_add")
     */
    public function add ($id,Request $req, UtilisateursRepository $rep,CartService $cartService)
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $em = $this->getDoctrine()->getManager();
        $reclamations= new Reclamation();
        #$reclamations->setCreatedAt(new \DateTimeImmutable());
        $reclamations->setEtat("En cours");
        $form=$this ->createForm(ReclamationType::class,$reclamations);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {$em=$this->getDoctrine()->getManager();
            $user=$rep->find($id);
            $reclamations->setClient($user);
            $em->persist($reclamations);
            $em->flush();
            $this->addFlash('success','Reclamation Added Successfully !');
            return $this->redirectToRoute('home');

        }

        return $this->render('reclamation/add.html.twig', [
            'formA'=>$form->createView(),
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }




    /**
     * @param ReclamationRepository $rep
     * @return Response
     * @Route("reclamation/list/{value}", name="r_list")
     */
    public function afficher($value, ReclamationRepository $rep,CartService $cartService): Response
    {    $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $reclamations=$rep->findById($value);
        return $this->render('reclamation/listReclamation.html.twig', [
            'tab' => $reclamations,
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }

    
    /** 
     * @Route("reclamation/etat/{id}", name="etat_rec")
     */
    public function afficheReponse($id, ReponseRepository $rep,CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();
        
       $reponse=$rep->find($id);
       #$reponseMessage=$reclamation->getReclamation();

        return $this->render('reclamation/reponse.html.twig', [
            'tab' => $reponse,
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }

    




}
