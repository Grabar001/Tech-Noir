<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxFiltreController extends AbstractController
{
    #[Route('/admin/ajax/load-filtres', name: 'admin_ajax_load_filtres')]
    public function loadFiltres(Request $request): Response
    {
        $categorieId = $request->query->get('category');

        if (!$categorieId) {
            return new Response('Catégorie manquante', Response::HTTP_BAD_REQUEST);
        }

        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($categorieId);

        if (!$categorie) {
            return new Response('Catégorie non trouvée', Response::HTTP_NOT_FOUND);
        }

        $filtres = $categorie->getFiltres();

        // ⛏ Отладка — временно включи
        // dump($filtres); die;

        return $this->render('admin/filtre_container.html.twig', [
            'filtres' => $filtres,
        ]);
    }
}