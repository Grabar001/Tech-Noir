<?php

namespace App\EasyAdmin\Field;

use App\Form\ProduitFiltreValeurSelectorType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

final class ProduitFiltreValeurField
{
    public static function new(string $propertyName, ?string $label = null): Field
    {
        return Field::new($propertyName, $label)
            ->setFormType(ProduitFiltreValeurSelectorType::class)
            ->setTemplatePath('@EasyAdmin/crud/field/array.html.twig')
            ->onlyOnForms();
    }
}