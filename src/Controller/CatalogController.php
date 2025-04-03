<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends AbstractController
{
    #[Route('/catalog/{slug}', name: 'catalog')]
public function index(
    string $slug,
    Request $request,
    CategorieRepository $categorieRepository,
    ProduitRepository $produitRepository
): Response {
    $categorie = $categorieRepository->findOneBy(['slug' => $slug]);

    if (!$categorie) {
        throw $this->createNotFoundException('Catégorie non trouvée');
    }

    $queryBuilder = $produitRepository->createQueryBuilder('p')
        ->where('p.categorie = :categorie')
        ->setParameter('categorie', $categorie);

    // 🔸 Фильтрация по marque
    if ($request->query->get('marque')) {
        $queryBuilder->andWhere('p.marque = :marque')
                     ->setParameter('marque', $request->query->get('marque'));
    }

    // 🔸 Фильтрация по memoire
    if ($request->query->get('memoire')) {
        $queryBuilder->andWhere('p.memoire = :memoire')
                     ->setParameter('memoire', $request->query->get('memoire'));
    }

    // ➕ Добавь другие фильтры по необходимости

    $produits = $queryBuilder->getQuery()->getResult();

    return $this->render('pages/catalog.html.twig', [
        'categorie' => $categorie,
        'produits' => $produits
    ]);
}

    #[Route('/catalog', name: 'catalog_all')]
    public function all(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('pages/catalog_all.html.twig', [
            'produits' => $produits
        ]);
    }
}