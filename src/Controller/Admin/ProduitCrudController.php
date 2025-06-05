<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, TextareaField, MoneyField, IntegerField, BooleanField, AssociationField, ImageField};

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('slug')->onlyOnDetail(),
            TextareaField::new('description'),
            MoneyField::new('prix')->setCurrency('EUR')->setStoredAsCents(false),
            IntegerField::new('reduction')->setLabel('Réduction (%)'),
            BooleanField::new('enStock')->setLabel('En stock'),
            BooleanField::new('nouveau')->setLabel('🆕 Nouveau'),
            AssociationField::new('categorie')->setLabel('Catégorie'),

            ImageField::new('image')
                ->setBasePath('/images/uploads/produits')
                ->setUploadDir('public/images/uploads/produits')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setRequired(false),

            AssociationField::new('filtreValeurs')
                ->setLabel('Valeurs de filtre')
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('required', false)
                ->onlyOnForms(),
        ];
    }

    public function deleteEntity(EntityManagerInterface $em, $entity): void
    {
        if (method_exists($entity, 'getCommandeProduits') && count($entity->getCommandeProduits()) > 0) {
            throw new EntityRemoveException('Produit lié à des commandes.');
        }
        parent::deleteEntity($em, $entity);
    }
}