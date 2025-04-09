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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityRemoveException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;


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

            IntegerField::new('reduction')
                ->setLabel('Réduction (%)')
                ->formatValue(function ($value) {
                    if (is_numeric($value) && $value > 0) {
                        return "🟢 -{$value}%";
                    }

                    return '—';
                }),

            BooleanField::new('enStock')
                ->renderAsSwitch(false)
                ->setLabel('En stock'),

            AssociationField::new('categorie')->setLabel('Catégorie'),

            ImageField::new('Image')
                ->setBasePath('/images/uploads/produits')
                ->setUploadDir('public/images/uploads/produits')
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



    public function deleteEntity(EntityManagerInterface $entityManager, $entity): void
    {
        if (count($entity->getCommandeProduits()) > 0) {
            throw new EntityRemoveException(
                'Impossible de supprimer ce produit car il est associé à une ou plusieurs commandes.'
            );
        }

        parent::deleteEntity($entityManager, $entity);
    }
    public function configureActions(Actions $actions): Actions
    {
        $goHome = Action::new('goHome', '🏠 Accueil du site')
            ->linkToUrl('/')
            ->setHtmlAttributes([
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
                'class' => 'btn btn-primary',
            ]);

        return $actions
            ->add(Crud::PAGE_INDEX, $goHome);
    }
}