<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class contentController extends AbstractController
{

    public function index(): Response
    { 
        return $this->render('content/index.html.twig', ['aCategoriesAnimal' => array("Chien", "Chats", "Reptiles", "Nac"), 'aCategoriesProducts' => array("Nourriture", "Médicament", "Jouets", "Habitat")]);
    }
}
