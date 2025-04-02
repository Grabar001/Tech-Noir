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
        TextField::new('slug')->hideOnIndex()->hideWhenCreating(),
        TextareaField::new('Description'),
        MoneyField::new('Prix')->setCurrency('EUR'),
        IntegerField::new('reduction'),

        BooleanField::new('isNew')
            ->renderAsSwitch(false)
            ->setLabel('Nouveau'),

        BooleanField::new('enStock')
            ->renderAsSwitch(false)
            ->setLabel('En stock'),

        AssociationField::new('categorie'),

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