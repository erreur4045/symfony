<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'profilePicture',
                FileType::class,
                [
                'attr' => ['placeholder' => 'Cliquer pour choisir une nouvelle image'],
                'label' => 'Choisissez votre nouvelle photo de profil aux formats .png .jpg .jpeg',
                'mapped' => false,
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
                        'mimeTypesMessage' => 'Seuls les formats .png .jpg .jpeg sont acceptés' ,
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
}
