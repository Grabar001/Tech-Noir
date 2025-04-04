<?php
namespace App\Controller\Admin;

use App\Entity\Filtre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AjaxFiltreController extends AbstractController
{
    #[Route('/admin/ajax/valeurs-par-filtre/{id}', name: 'ajax_valeurs_par_filtre')]
    public function valeursParFiltre(Filtre $filtre, Request $request): JsonResponse
    {
        $valeurs = $filtre->getFiltreValeurs();

        $data = [];
        foreach ($valeurs as $valeur) {
            $data[] = [
                'id' => $valeur->getId(),
                'valeur' => $valeur->getValeur(),
            ];
        }

        return new JsonResponse($data);
    }
}