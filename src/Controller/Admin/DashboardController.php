<?php

namespace App\Controller\Admin;

use App\Entity\Filtre;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Categorie;
use App\Entity\FiltreValeur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(ProduitCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TECH NOIR');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('🏠 Accueil du site', 'fas fa-home', '/')
            ->setLinkTarget('_blank');

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Commandes', 'fas fa-receipt', Commande::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Produit::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class);
        yield MenuItem::linkToCrud('Filtres', 'fas fa-filter', Filtre::class);
        yield MenuItem::linkToCrud('Valeurs de filtre', 'fas fa-tags', FiltreValeur::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}