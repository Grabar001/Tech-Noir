<?php

// src/Controller/Admin/ProduitCrudController.php
namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitFiltreValeurSelectorType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Crud, Actions, Filters, Action, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    TextareaField,
    MoneyField,
    IntegerField,
    BooleanField,
    AssociationField,
    ImageField,
    Field,
    FormField
};
use EasyCorp\Bundle\EasyAdminBundle\Filter\{
    EntityFilter,
    BooleanFilter,
    NumericFilter
};
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityRemoveException;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('nom');
        yield TextField::new('slug')->onlyOnDetail();
        yield TextareaField::new('description');
        yield MoneyField::new('prix')
            ->setCurrency('EUR')
            ->setStoredAsCents(false);
        yield BooleanField::new('isNew')->setLabel('🆕 Nouveau');
        yield IntegerField::new('reduction')->setLabel('Réduction (%)');
        yield BooleanField::new('enStock')->setLabel('En stock');
        yield AssociationField::new('categorie')->setLabel('Catégorie');
        yield ImageField::new('image')
            ->setBasePath('/images/uploads/produits')
            ->setUploadDir('public/images/uploads/produits')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired(false);


        yield FormField::addPanel('Filtres personnalisés');

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setSearchFields(['nom', 'description'])
            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('categorie'))
            ->add(BooleanFilter::new('isNew'))
            ->add(BooleanFilter::new('enStock'))
            ->add(NumericFilter::new('reduction'))
            ->add(NumericFilter::new('prix'));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('goHome', '🏠 Accueil')->linkToUrl('/'));
    }

    public function configureCrudFormBuilder(FormBuilderInterface $formBuilder, string $pageName, KeyValueStore $crudData): void
    {
        $produit = $crudData->get('entity')->getInstance();

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $formBuilder->add('produitFiltreValeurs', ProduitFiltreValeurSelectorType::class, [
                'mapped' => false,
                'required' => false,
                'product' => $produit,
            ]);
        }
    }

    public function deleteEntity(EntityManagerInterface $em, $entity): void
    {
        if (method_exists($entity, 'getCommandeProduits') && count($entity->getCommandeProduits()) > 0) {
            throw new EntityRemoveException('Produit lié à des commandes.');
        }
        parent::deleteEntity($em, $entity);
    }
}