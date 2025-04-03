<?php

namespace App\Controller\Admin;

use App\Entity\FiltreValeur;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FiltreValeurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FiltreValeur::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        TextField::new('valeur')->setLabel('Valeur'),
        AssociationField::new('filtre')->setLabel('Filtre')
    ];
}
}
