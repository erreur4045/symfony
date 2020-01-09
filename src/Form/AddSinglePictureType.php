<?php

namespace App\Form;

use App\Entity\Pictureslink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddSinglePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', FileType::class,[
                'attr' => ['placeholder' => 'Cliquer pour choisir une nouvelle image'],
                'label' => 'Choisisez votre nouvelle photo au formats .png .jpg .jpeg',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            "image/png",
                            "image/jpeg",
                            "image/jpg",
                        ],
                        'mimeTypesMessage' => 'Seul les formats .png .jpg .jpeg sont accepté' ,
                    ])
                ],
            ])
            ->add('alt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pictureslink::class,
            'translation_domain' => 'form_add_picture'
        ]);
    }
}
