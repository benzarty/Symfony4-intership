<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class OffreUploadUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cv', FileType::class, ['mapped' => false])
            ->add('q1',TextType::class,['label' => '
Pourquoi vous avez choisie cette entreprise ?',
                'attr' => ['placeholder' => 'please write a reason']])
            ->add('q2',TextType::class,['label' => 'Avez-vous déjà travaillé dans un emploi similaire et dans quelle opération ?',
                'attr' => ['placeholder' => 'please write a reason']])
            ->add('q3',TextType::class,['label' => 'Depuis combien de temps y travaillez-vous ?',
                'attr' => ['placeholder' => 'please write a number(monthes)']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
