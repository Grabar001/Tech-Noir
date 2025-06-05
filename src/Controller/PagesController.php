<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    #[Route('/conditions-generales', name: 'conditions')]
    public function conditions(): Response
    {
        return $this->render('pages/conditions.html.twig');
    }


    #[Route('/mentions-legales', name: 'mentions')]
    public function mentions(): Response
    {
        return $this->render('pages/mentions.html.twig');
    }
}