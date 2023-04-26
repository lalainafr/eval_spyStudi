<?php

namespace App\Form;

use App\Entity\Target;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TargetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', null, [
            'label' => 'Prenom',
        ])
        ->add('lastName', null, [
            'label' => 'Nom',
        ])
        ->add('birthDate', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date d\'anniversaire',
        ])
        ->add('codeName', null, [
            'label' => 'Nom de code',

        ])
        ->add('nationality', EntityType::class, [
            'class' => Country::class,
            'label' => 'Pays'
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Valider',
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Target::class,
        ]);
    }
}
