<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;




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

            TextField::new('Nom'),
            TextField::new('slug')->onlyOnDetail(), 
            TextareaField::new('Description'),
            MoneyField::new('Prix')->setCurrency('EUR'),

            BooleanField::new('isNew')
                ->setLabel('🆕 Nouveau')
                ->formatValue(fn($value) => $value ? 'Oui' : 'Non'),

            TextField::new('reduction')
                ->setLabel('Réduction')
                ->formatValue(fn($value) => $value > 0 ? "🟢 -{$value}%" : '—')
                ->onlyOnIndex(),

            IntegerField::new('reduction')
                ->setLabel('Réduction')
                ->onlyOnForms(),

            BooleanField::new('enStock')
                ->renderAsSwitch(false)
                ->setLabel('En stock'),

            AssociationField::new('categorie')->setLabel('Catégorie'),

            ImageField::new('Image')
                ->setBasePath('/uploads/produits')
                ->setUploadDir('public/uploads/produits')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setRequired(false),

        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('categorie'))
            ->add(BooleanFilter::new('isNew'))
            ->add(BooleanFilter::new('enStock'))
            ->add(NumericFilter::new('reduction'))
            ->add(NumericFilter::new('Prix'));
    }
}