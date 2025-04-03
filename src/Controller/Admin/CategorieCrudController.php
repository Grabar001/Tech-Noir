<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\FiltreType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('nom'),

            TextField::new('slug')->onlyOnDetail(),

            ImageField::new('image')
                ->setBasePath('/uploads/categories')
                ->setUploadDir('public/uploads/categories')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setRequired(false),

            CollectionField::new('filtres')
                ->setLabel('Filtres de cette catégorie')
                ->onlyOnForms()
                ->setEntryType(FiltreType::class) // 👈 форма с вложенными значениями
                ->setEntryIsComplex(true)
                ->allowAdd()
                ->allowDelete()
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $customDelete = Action::new('supprimer_avec_confirmation', 'Supprimer', 'fa fa-trash')
            ->linkToRoute('admin_categorie_custom_delete', fn(Categorie $c) => ['id' => $c->getId()]);

        return $actions
            ->disable(Action::DELETE)
            ->add(Action::INDEX, $customDelete)
            ->add(Action::DETAIL, $customDelete);
    }

    #[Route('/admin/categorie/{id}/delete', name: 'admin_categorie_custom_delete', methods: ['GET'])]
    public function customDelete(int $id, EntityManagerInterface $em): Response
    {
        $categorie = $em->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            $this->addFlash('danger', '❌ Catégorie non trouvée.');
            return $this->redirect('/admin?crudControllerFqcn=App\Controller\Admin\CategorieCrudController');
        }

        if ($categorie->getProduits()->count() > 0) {
            $this->addFlash('danger', '❌ Impossible de supprimer cette catégorie : des produits y sont encore associés.');
            return $this->redirect('/admin?crudControllerFqcn=App\Controller\Admin\CategorieCrudController');
        }

        $em->remove($categorie);
        $em->flush();

        $this->addFlash('success', '✅ Catégorie supprimée avec succès.');
        return $this->redirect('/admin?crudControllerFqcn=App\Controller\Admin\CategorieCrudController');
    }
}