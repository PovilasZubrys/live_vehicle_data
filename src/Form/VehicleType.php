<?php

namespace App\Form;

use App\Entity\device;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Choose your vehicle' => '',
                    'car' => 'car',
                    'motorcycle' => 'motorcycle',
                    'van' => 'van'
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('make', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Vehicle make',
                ],
                'label' => 'Vehicle make',
            ])
            ->add('model', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Vehicle model',
                ],
                'label' => 'Vehicle model',
            ])
            ->add('year', NumberType::class, [
                'label' => 'Vehicle year',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Vehicle year',
                ]
            ])
            ->add('Save', SubmitType::class, [
                'label' => 'Save vehicle',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
