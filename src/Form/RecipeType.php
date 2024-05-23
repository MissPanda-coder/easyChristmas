<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\RecipeDifficulty;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('photo')
            ->add('duration')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('isActive')
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('recipeCategory', EntityType::class, [
                'class' => RecipeCategory::class,
                'choice_label' => 'id',
            ])
            ->add('recipeDifficulty', EntityType::class, [
                'class' => RecipeDifficulty::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
