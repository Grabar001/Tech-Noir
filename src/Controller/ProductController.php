<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/catalog', name: 'catalog')]
    public function catalog(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('pages/catalog.html.twig', [
            'title' => 'Catalogue des produits - TECH NOIR',
            'produits' => $produits, 
        ]);
    }

    #[Route('/product/{id}', name: 'product')]
    public function product(int $id, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        return $this->render('pages/product.html.twig', [
            'title' => 'Produit - TECH NOIR',
            'produit' => $produit, 
        ]);
    }
}