<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Recipecategory;
use App\Entity\Recipedifficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'label' => 'Brève description de la recette',
            ])
            ->add('photo', FileType::class, [
                'label' => 'Image de la recette',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez soumettre une image de type JPG ou PNG.',
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'Votre image fait {{ size }} {{ suffix }}. La limite est de {{ limit }} {{ suffix }}'
                    ]),
                ],
            ])
            ->add('duration', TimeType::class, [
                'label' => 'Temps de préparation',
                'input' => 'datetime',
                'widget' => 'choice',
                'hours' => range(0, 23),
                'minutes' => range(0, 59),
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
            ->add('servings', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'attr' => ['class' => 'form_input']
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => RecipeHasIngredientType::class,
                'entry_options' => [
                    'label' => false,
                    'ingredients' => $options['ingredients'],
                    'units' => $options['units'],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Ingrédients',
            ])
            ->add('recipestep', CollectionType::class, [
                'entry_type' => RecipestepsType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Étapes de préparation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'ingredients' => [], 
            'units' => [], 
        ]);
    }
}
