<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse email',
                    'class' => 'form-control'
                ]
            ])
            ->add('location_longitude', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la longitude',
                    'class' => 'form-control'
                ]
            ])
            ->add('location_latitude', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la latitude',
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
