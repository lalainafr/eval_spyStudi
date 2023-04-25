<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Contact;
use App\Entity\Country;
use App\Entity\Hideout;
use App\Entity\Mission;
use App\Entity\Speciality;
use App\Entity\Status;
use App\Entity\Target;
use App\Entity\Type;
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
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'label' => 'Type',
                'placeholder' => 'Choisir le type de la mission'
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'label' => 'Statut',
                'placeholder' => 'Choisir le statut de la mission'

            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => 'Pays',
                'placeholder' => 'Choisir le pays de la mission'

            ])
            ->add('speciality', EntityType::class, [
                'class' => Speciality::class,
                'label' => 'Specilaité requise',
                'placeholder' => 'Choisir une spécialité'

            ])
            ->add('hideout', EntityType::class, [
                'class' => Hideout::class,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Planque'
            ])
            ->add('target', EntityType::class, [
                'class' => Target::class,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Cible'
            ])
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Contact'
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
