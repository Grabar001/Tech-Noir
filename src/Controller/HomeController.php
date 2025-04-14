<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $produits = $produitRepository->findProduitsAvecReduction();
        $categories = $categorieRepository->findAll();

        return $this->render('pages/home.html.twig', [
            // 'title' => 'Accueil TECH NOIR',
            'produits' => $produits,
            'categories' => $categories
        ]);
    }

    #[Route('/promotions', name: 'promotion')]
    public function promotions(): Response
    {
        return $this->render('promotion/index.html.twig');
    }
}