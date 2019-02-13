<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    // Enregistrement d'un utilisateur

    /**
     * @Route("/register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //Objet User
        $user = new User();

        // Génération du formulaire
        $form = $this->createForm(UserType::class, $user);

        // Traitement du formulaire (uniquement s'il a été soumis)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encodage du mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // Utilisateur ajouté dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //Redirection vers la liste des figures
            return $this->redirectToRoute('app_trick_list');
        }

        // Appel du template
        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }

    // Profil d'un utilisateur
    
    /**
     * @Route("/profile/{id}")
     */
    public function profile(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Génération du formulaire
        $form = $this->createForm(UserType::class, $user);

        // Traitement du formulaire (uniquement s'il a été soumis)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encodage du mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // Utilisateur mis à jour dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        // Appel du template
        return $this->render('user/profile.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
