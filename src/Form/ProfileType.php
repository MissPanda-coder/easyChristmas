<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('firstname', TextType::class, ['required' => false])
            ->add('lastname', TextType::class, ['required' => false])
            ->add('photoFile', FileType::class, [   
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(
                      [
                        'mimeTypesMessage' => 'Veuillez soumettre une image',
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'Votre image fait {{ size }} {{ suffix }}. La limite est de {{ limit }} {{ suffix }}'
                      ]),
                    ],
                ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
