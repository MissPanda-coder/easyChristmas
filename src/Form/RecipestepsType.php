<?php

namespace App\Form;

use App\Entity\Recipestep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RecipestepsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('stepNumber', IntegerType::class, ['label' => 'Numéro de l\'étape'])
        ->add('description', TextType::class, ['label' => 'Description'])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipestep::class,
        ]);
    }
}
