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

        $filtres = $categorie->getFiltres();

        $queryBuilder = $produitRepository->createQueryBuilder('p')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $categorie);

        // 🔸 Примеры стандартных фильтров
        if ($request->query->get('marque')) {
            $queryBuilder->andWhere('p.marque = :marque')
                ->setParameter('marque', $request->query->get('marque'));
        }

        if ($request->query->get('memoire')) {
            $queryBuilder->andWhere('p.memoire = :memoire')
                ->setParameter('memoire', $request->query->get('memoire'));
        }

        // 🔸 Гибкая фильтрация по фильтрам, привязанным к категории
        foreach ($filtres as $filtre) {
            $paramName = 'filter_' . $filtre->getId();

            if ($request->query->has($paramName)) {
                $valeurs = $request->query->all($paramName);

                if (is_array($valeurs) && !empty($valeurs)) {
                    $champ = $filtre->getChamp(); // ← безопасное техническое имя поля
                    if ($champ) {
                        $queryBuilder->andWhere('p.' . $champ . ' IN (:valeurs_' . $filtre->getId() . ')')
                            ->setParameter('valeurs_' . $filtre->getId(), $valeurs);
                    }
                }
            }
        }

        $produits = $queryBuilder->getQuery()->getResult();

        return $this->render('pages/catalog.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
            'filtres' => $filtres
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