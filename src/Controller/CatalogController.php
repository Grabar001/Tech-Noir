<?php

namespace App\Controller;

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
        $valeurs = $request->query->all($paramName);

        if (is_array($valeurs) && !empty($valeurs)) {
            $champ = $filtre->getChamp();
            if ($champ) {
                $champ = preg_replace('/[^a-zA-Z0-9_]/', '', $champ);
                $queryBuilder->andWhere("p.{$champ} IN (:valeurs_{$filtre->getId()})")
                    ->setParameter("valeurs_{$filtre->getId()}", $valeurs);
            }
        }
    }

    $sort = $request->query->get('sort');
    switch ($sort) {
        case 'reduction_desc':
            $queryBuilder->orderBy('p.reduction', 'DESC');
            break;
        case 'reduction_asc':
            $queryBuilder->orderBy('p.reduction', 'ASC');
            break;
        case 'prix_asc':
            $queryBuilder->orderBy('p.prix', 'ASC');
            break;
        case 'prix_desc':
            $queryBuilder->orderBy('p.prix', 'DESC');
            break;
        case 'nom_asc':
            $queryBuilder->orderBy('p.nom', 'ASC');
            break;
        case 'nom_desc':
            $queryBuilder->orderBy('p.nom', 'DESC');
            break;
        case 'stock':
            $queryBuilder->andWhere('p.enStock = true');
            break;
        case 'rupture':
            $queryBuilder->andWhere('p.enStock = false');
            break;
    }

    // 🔸 Пагинация
    $produits = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        12
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
}