<?php

namespace App\Form;

use App\Entity\Wishes;
use App\Entity\Assignation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('wishnames', CollectionType::class, [
            'entry_type' => TextType::class,
            'entry_options' => [
                'label' => false,
                'attr' => ['placeholder' => 'Nom du voeu'],
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
            'prototype' => true,
            'by_reference' => false,
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wishes::class,
        ]);
    }
}
