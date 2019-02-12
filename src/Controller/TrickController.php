<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    // Liste des figures (Page d'accueil)
    
    /**
     * @Route("/")
     */
    public function list()
    {               
        // Appel du template
        return $this->render('trick/list.html.twig');
    }
}
