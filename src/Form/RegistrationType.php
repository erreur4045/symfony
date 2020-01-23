<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('password', PasswordType::class)
            ->add('mail')
            ->add(
                'profilePicture',
                FileType::class,
                [
                'label' => 'Choisisez votre nouvelle photo de profile au formats .png .jpg .jpeg',
                'required' => false,
                'constraints' => [
                    new File(
                        [
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            "image/png",
                            "image/jpeg",
                            "image/jpg",
                        ],
                        'mimeTypesMessage' => 'Seul les formats .png .jpg .jpeg sont accepté' ,
                        ]
                    )
                ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );
    }
    public function getSalt()
    {
    }
}
