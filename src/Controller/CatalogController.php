<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        // 🔹 Получение категории
        $categorie = $categorieRepository->findOneBy(['slug' => $slug]);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // 🔹 Получение фильтров категории
        $filtres = $categorie->getFiltres();

        // 🔹 Построение базового запроса к продуктам
        $queryBuilder = $produitRepository->createQueryBuilder('p')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $categorie);

        // 🔹 Применение фильтров (filter_*)
        foreach ($filtres as $filtre) {
            $paramName = 'filter_' . $filtre->getId();
            $valeurs = $request->query->all($paramName);

            if (is_array($valeurs) && !empty($valeurs)) {
                $aliasPfv = 'pfv_' . $filtre->getId();
                $aliasFv = 'fv_' . $filtre->getId();

                $queryBuilder
                    ->join("p.produitFiltreValeurs", $aliasPfv)
                    ->join("$aliasPfv.filtreValeur", $aliasFv)
                    ->andWhere("$aliasFv.valeur IN (:valeurs_{$filtre->getId()})")
                    ->setParameter("valeurs_{$filtre->getId()}", $valeurs);
            }
        }

        // 🔹 Обработка сортировки ДО paginate()
        $sortParam = $request->query->get('sort', 'nom_asc');

        if (in_array($sortParam, ['stock', 'rupture'])) {
            if ($sortParam === 'stock') {
                $queryBuilder->andWhere('p.enStock = true');
            } elseif ($sortParam === 'rupture') {
                $queryBuilder->andWhere('p.enStock = false');
            }

            // ⛔ Удаляем параметр сортировки из запроса
            $request->query->remove('sort');
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

        // 🔹 Пагинация
        $produits = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            12,
            ['sortFieldParameterName' => null] // ❌ KnpPaginator не сортирует
        );

        // 🔹 Рендер шаблона
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