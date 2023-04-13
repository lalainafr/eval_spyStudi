<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Mission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null , [
                'label' => 'Titre de la mission'
            ])
            ->add('description')
            ->add('codeName', TextType::class, [
                'label' => 'Nom de code'
            ])
            ->add('startDate', null, [
                'widget' => 'single_text',
                'label' => 'Début de la mission',

            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
                'label' => 'Fin de la mission',

            ])
            ->add('agent', EntityType::class, [
                'class' => Agent::class,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Agent'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
