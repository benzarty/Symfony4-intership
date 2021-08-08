<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datedebut',DateType::class, [
                'placeholder' => 'Select a value',
            ])
            ->add('datefin',DateType::class, [
                'placeholder' => 'Select a value',
            ])
            ->add('libele')
            ->add('categorie',ChoiceType::class, [
                'choices' => [
                    'emmision d appel' => 'emmisiondappel',
                    'voyance' => 'voyance',
                    'reception d appel' => 'receptiondappel',
                ],

            ])
            ->add('photo',FileType::class, ['mapped' =>false])

            ->add('description',TextareaType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}