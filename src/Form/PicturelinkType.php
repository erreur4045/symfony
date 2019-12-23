<?php

namespace App\Form;

use App\Entity\Pictureslink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class PicturelinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', FileType::class, [
                'label' => 'Votre image',

                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            "image/png",
                            "image/jpeg",
                            "image/jpg",
                            "image/gif",
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier conforme',
                    ])
                ]
            ])
            ->add('alt', TextType::class, ['label' => 'Desciption de l\'image'])
            // todo : une seul imagefirst possible ?
            ->add('first_image', CheckboxType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => 'Image à la Une ?',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pictureslink::class,
        ]);
    }
}