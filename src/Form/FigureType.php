<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Titre de la figure'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('idfiguregroup')
            ->add(
                'pictureslinks',
                CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => PicturelinkType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            )
            ->add(
                'videolinks',
                CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => VideolinkType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Figure::class,
            'translation_domain' => 'forms'
            ]
        );
    }
}
