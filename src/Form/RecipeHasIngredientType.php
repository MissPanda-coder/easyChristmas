<?php

namespace App\Form;

use App\Entity\Unit;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\RecipeHasIngredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RecipeHasIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('ingredient', EntityType::class, [
            'class' => Ingredient::class,
            'choice_label' => 'ingredientName',
            'label' => 'Ingrédient',  
            'placeholder' => 'Sélectionnez un ingrédient',
            'attr' => ['class' => 'ingredient-select']
        ])
        ->add('unit', EntityType::class, [
            'class' => Unit::class,
            'choice_label' => 'unitName',
            'label' => 'Unité de mesure', 
            'placeholder' => 'Sélectionnez une unité',
            'attr' => ['class' => 'unit-select']
        ])
        ->add('quantity', IntegerType::class, [
            'label' => 'Quantité',
            'attr' => ['class' => 'quantity-input']
        ]);

            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeHasIngredient::class,
        ]);
    }
}