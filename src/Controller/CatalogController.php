<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogController extends AbstractController
{
    #[Route('/catalog/{slug}', name: 'catalog')]
    public function index(
        string $slug,
        Request $request,
        CategorieRepository $categorieRepository,
        ProduitRepository $produitRepository,
        PaginatorInterface $paginator
    ): Response {
        $categorie = $categorieRepository->findOneBy(['slug' => $slug]);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $filtres = $categorie->getFiltres();

        $queryBuilder = $produitRepository->createQueryBuilder('p')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $categorie);


        foreach ($filtres as $filtre) {
    $paramName = 'filter_' . $filtre->getId();

    // Получаем весь массив GET-параметров
    $allParams = $request->query->all();

    // Значения фильтра
    $valeurs = $allParams[$paramName] ?? [];

    if (!is_array($valeurs)) {
        $valeurs = [$valeurs];
    }

    if (!empty($valeurs)) {
        $aliasFv = 'fv_' . $filtre->getId();

        $queryBuilder
            ->join('p.filtreValeurs', $aliasFv)
            ->andWhere("$aliasFv.valeur IN (:valeurs_{$filtre->getId()})")
            ->setParameter("valeurs_{$filtre->getId()}", $valeurs);
    }
}


        $sortParam = $request->query->get('sort', 'nom_asc');

        if (in_array($sortParam, ['stock', 'rupture'])) {
            if ($sortParam === 'stock') {
                $queryBuilder->andWhere('p.enStock = true');
            } elseif ($sortParam === 'rupture') {
                $queryBuilder->andWhere('p.enStock = false');
            }
        } elseif (strpos($sortParam, '_') !== false) {
            [$sortField, $sortDirection] = explode('_', $sortParam);

            $sortMap = [
                'prix' => 'p.prix',
                'nom' => 'p.nom',
                'reduction' => 'p.reduction',
            ];

            if (isset($sortMap[$sortField])) {
                $queryBuilder->orderBy($sortMap[$sortField], strtoupper($sortDirection));
            }
        }

        $produits = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            12,
            ['sortFieldParameterName' => null]
        );

        return $this->render('pages/catalog.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
            'filtres' => $filtres,
        ]);
    }

    #[Route('/catalog', name: 'catalog_all')]
    public function all(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('pages/catalog_home.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/produit/{slug}', name: 'product_show')]
    public function show(Produit $produit): Response
    {
        return $this->render('product/show.html.twig', [
            'produit' => $produit,
        ]);
    }
}
