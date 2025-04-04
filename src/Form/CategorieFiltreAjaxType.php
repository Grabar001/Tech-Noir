<?php

namespace App\Form;

use App\Entity\Filtre;
use App\Entity\FiltreValeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieFiltreAjaxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filtre', EntityType::class, [
                'class' => Filtre::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un filtre',
                'attr' => [
                    'data-action' => 'change->filtre#loadValeurs',
                    'data-filtre-target' => 'filtreSelect'
                ]
            ])
            ->add('valeurs', EntityType::class, [
                'class' => FiltreValeur::class,
                'choices' => [],
                'choice_label' => 'valeur',
                'multiple' => true,
                'attr' => [
                    'data-filtre-target' => 'valeursSelect'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
