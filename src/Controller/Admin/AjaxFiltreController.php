<?php

namespace App\Controller\Admin;

use App\Form\ProduitFiltreValeurSelectorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxFiltreController extends AbstractController
{
    #[Route('/admin/ajax/filtre/{category}', name: 'admin_ajax_filtre')]
    public function filtre(Request $request, int $category): Response
    {
        $form = $this->createForm(ProduitFiltreValeurSelectorType::class, null, [
            'categorie_id' => $category
        ]);

        return $this->render('admin/fields/_produit_filtre_valeur_widget.html.twig', [
            'form' => $form->createView()
        ]);
    }
}