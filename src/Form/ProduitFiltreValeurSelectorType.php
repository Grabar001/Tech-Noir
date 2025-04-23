<?php

namespace App\Form;

use App\Entity\Filtre;
use App\Entity\FiltreValeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitFiltreValeurSelectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('produitFiltreValeurs', EntityType::class, [
            'class' => FiltreValeur::class,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => function (FiltreValeur $valeur) {
                return $valeur->getFiltre()->getNom() . ' — ' . $valeur->getValeur();
            },
            'group_by' => function (FiltreValeur $valeur) {
                return $valeur->getFiltre()->getNom();
            },
            'mapped' => false,
            'required' => false,
            'label' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}