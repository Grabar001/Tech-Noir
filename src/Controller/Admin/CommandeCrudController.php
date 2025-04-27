<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield DateTimeField::new('createdAt', 'Date de commande');
        yield MoneyField::new('total')->setCurrency('EUR');

        yield ChoiceField::new('status', 'Statut')
            ->setChoices([
                '🕒 En attente' => 'en_attente',
                '📦 Expédiée' => 'expedie',
                '✅ Livrée' => 'livre',
                '❌ Annulée' => 'annule',
            ])
            ->renderAsBadges([
                'en_attente' => 'warning',
                'expedie' => 'info',
                'livre' => 'success',
                'annule' => 'danger',
            ]);

            return [
                IdField::new('id')->onlyOnIndex(),
                TextField::new('status')->setLabel('Statut'),
                CollectionField::new('commandeProduits')
                    ->onlyOnDetail()
                    ->setTemplatePath('admin/fields/commande_produits.html.twig')
                    ->setLabel('Produits commandés'),
            ];
    }
}
