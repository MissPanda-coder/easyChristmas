<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Recipecategory;
use App\Entity\Recipedifficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserRecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la recette',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('photo', TextType::class, [
                'label' => 'Image de la recette (URL)',
            ])
            ->add('duration', TextType::class, [
                'label' => 'Temps de préparation',
            ])
            ->add('recipecategory', EntityType::class, [
                'class' => Recipecategory::class,
                'choice_label' => 'categoryname',
                'label' => 'Catégorie',
            ])
            ->add('recipedifficulty', EntityType::class, [
                'class' => Recipedifficulty::class,
                'choice_label' => 'difficultyname',
                'label' => 'Difficulté',
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => RecipeHasIngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Ingrédients',
            ])
            ->add('recipestep', CollectionType::class, [
                'entry_type' => RecipestepsType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Étapes',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}

