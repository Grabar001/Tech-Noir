<?php

namespace App\Controller\Admin;

use App\Entity\Filtre;
use App\Form\FiltreValeurType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class FiltreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Filtre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom')
                ->setLabel('Nom du filtre'),

            AssociationField::new('categorie')
                ->setLabel('Catégorie'),

            CollectionField::new('filtreValeurs')
                ->setLabel('Valeurs possibles')
                ->onlyOnForms()
                ->setEntryType(FiltreValeurType::class) 
                ->setEntryIsComplex(true)
                ->allowAdd()
                ->allowDelete()
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
}