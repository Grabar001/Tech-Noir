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
        // 🔸 Получаем категорию по slug
        $categorie = $categorieRepository->findOneBy(['slug' => $slug]);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // 🔸 Получаем фильтры, связанные с категорией
        $filtres = $categorie->getFiltres();

        // 🔸 Базовый запрос: выбираем продукты этой категории
        $queryBuilder = $produitRepository->createQueryBuilder('p')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $categorie);

        // 🔸 Динамическая фильтрация по фильтрам
        foreach ($filtres as $filtre) {
            $paramName = 'filter_' . $filtre->getId();
            $valeurs = $request->query->all($paramName);

            if (is_array($valeurs) && !empty($valeurs)) {
                $champ = $filtre->getChamp(); // ⚠️ ← Убедись, что метод и поле champ существуют
                if ($champ) {
                    // Предотвращаем SQL-инъекцию и ошибки синтаксиса
                    $champ = preg_replace('/[^a-zA-Z0-9_]/', '', $champ);

                    $queryBuilder->andWhere("p.{$champ} IN (:valeurs_{$filtre->getId()})")
                        ->setParameter("valeurs_{$filtre->getId()}", $valeurs);
                }
            }
        }

        // 🔸 Получаем готовый результат
        $produits = $queryBuilder->getQuery()->getResult();

        // 🔸 Отдаем шаблону все нужные переменные
        return $this->render('pages/catalog.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
            'filtres' => $filtres
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