<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ])
                ]
            ])
            ->add('date', DateType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la date'
                ]
            ])
            ->add('location_longitude', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la longitude',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La longitude ne peut pas être vide.',
                    ])
                ]
            ])
            ->add('location_latitude', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la latitude',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La latitude ne peut pas être vide.',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
