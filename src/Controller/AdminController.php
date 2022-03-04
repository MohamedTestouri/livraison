<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use App\Repository\DemandesRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("admin" , name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @return Reponse
     * @Route("/dashboard/listU", name="userlist")
     */
    public function afficherUser(UtilisateursRepository $rep){
        $users=$rep->findAll();
        return $this->render('/dashboard/userslist.html.twig', [
            'users' => $users,
        ]);
    }
     /**
     * @return Reponse
     * @Route("/dashboard/listD", name="demandelist")
     */
    public function afficherDemande(DemandesRepository $rep){
        $demandes=$rep->findAll();
        return $this->render('/dashboard/demandelist.html.twig', [
            'demandes' => $demandes,
        ]);
    }
     /**
     * @return Reponse
     * @Route("/dashboard/listU/delete/{id}", name="userdelete")
     */

    public function DeleteUser($id,UtilisateursRepository $rep){
        $user=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_userlist');
    }

     /**
     * @return Reponse
     * @Route("/dashboard/listD/delete/{id}", name="demdelete")
     */

    public function DeleteDem($id,DemandesRepository $rep){
        $dem=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($dem);
        $em->flush();

        return $this->redirectToRoute('admin_demandelist');
    }

    /**
     * @return Reponse
     * @Route("/dashboard/listD/accept/{id}", name="demaccept")
     */

    public function AcceptDem($id,DemandesRepository $rep, UserPasswordEncoderInterface $encoder){
        $dem=$rep->find($id);
        $user= new Utilisateurs();
        $user->setNom($dem->getNom());
        $user->setPrenom($dem->getPrenom());
        $user->setEmail($dem->getEmail());
        $user->setTelephone($dem->getTelephone());
        $user->setRole($dem->getRole());

        $hash = $encoder->encodePassword($user, $dem->getPassword());
        $user->setPassword($hash);

        $user->setQuestionSecurite1($dem->getQuestionSecurite1());
        $user->setQuestionSecurite2($dem->getQuestionSecurite2());

        $user->setEtat($dem->getEtat());


        $em=$this->getDoctrine()->getManager();
        $em->remove($dem);
        $em->Persist($user);
        $em->flush();

        return $this->redirectToRoute('admin_demandelist');
    }

     /**
     * @Route("/loginadmin", name="loginadmin")
     */
    public function login(): Response
    {
        return $this->render('dashboard/loginadmin.html.twig');
    }
     /**
     * @Route("/logout", name="logoutadmin")
     */
    public function logout(){}
}
