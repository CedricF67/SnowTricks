<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Connexion d'un utilisateur

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupération des erreurs de login, s'il y en as
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Appel du template
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // Mot de passe oublié

    /**
     * @Route ("/forgottenPassword", name="app_security_forgottenPassword")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        // Si l'utilisateur à soumis le formulaire pour reset son mot de passe
        if ($request->isMethod('POST')) {
 
            // Récupération du nom de l'utilisateur dans la requête
            $username = $request->request->get('username');
 
            // Récupération de l'utilisateur correspondant dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
 
            // Si l'utilisateur n'existe pas, une erreur est retournée
            if ($user === null) {
                $this->addFlash('danger', 'Utilisateur Inconnu');

                // Redirection vers la liste des figures
                return $this->redirectToRoute('app_trick_list');
            }

            // Génération d'un token pour l'utilisateur
            $token = $tokenGenerator->generateToken();
 
            // Tentative de lien entre le token et l'utilisateur
            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } 
            
            // Affichage d'une éventuelle erreur
            catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                // Redirection vers la liste des figures
                return $this->redirectToRoute('app_trick_list');
            }
 
            // Génération de l'url ou l'utilisateur devra se rendre pour reset son mot de passe
            $url = $this->generateUrl('app_security_resetPassword', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
 
            // Génération du message qui sera envoyé à l'utilisateur
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('snowtricks@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Voici un lien pour réinitialiser votre mot de passe : " . $url,
                    'text/html'
                );
 
            // Envoi du message
            $mailer->send($message);
 
            $this->addFlash('success', 'Mail envoyé');
 
            // Redirection vers la liste des figures
            return $this->redirectToRoute('app_trick_list');
        }

        // Génération du template
        return $this->render('security/forgottenPassword.html.twig');
    }

    // Reset du mot de passe

    /**
     * @Route("/resetPassword/{token}", name="app_security_resetPassword")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Si l'utilisateur a soumis le formulaire avec son token de reset de mot de passe
        if ($request->isMethod('POST')) {

            // Stockage de l'appel du manager de doctrine
            $entityManager = $this->getDoctrine()->getManager();
 
            // Récupération de l'utilisateur correspondant au token
            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
 
            // Si le token n'existe pas, une erreur est retournée
            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');

                // Redirection vers la liste des figures
                return $this->redirectToRoute('app_trick_list');
            }
 
            // Suppression du token assigné à l'utilisateur
            $user->setResetToken(null);

            // Changement du mot de passe de l'utilisateur
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();
 
            $this->addFlash('notice', 'Mot de passe mis à jour');
 
            // Redirection vers la liste des figures
            return $this->redirectToRoute('app_trick_list');
        }else {
            
            // Génération du template
            return $this->render('security/resetPassword.html.twig', ['token' => $token]);
        }
 
    }
}
