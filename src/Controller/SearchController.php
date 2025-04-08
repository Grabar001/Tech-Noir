<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function search(Request $request, ProduitRepository $produitRepository): Response
    {
        $query = $request->query->get('q');

        if (!$query) {
            return $this->redirectToRoute('homepage');
        }

        $produits = $produitRepository->createQueryBuilder('p')
            ->where('p.nom LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        return $this->render('search/search_results.html.twig', [
            'query' => $query,
            'produits' => $produits,
        ]);
    }
}