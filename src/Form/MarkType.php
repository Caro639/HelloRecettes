<?php

namespace App\Form;

use App\Entity\Mark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mark', ChoiceType::class, [
                'choices' => [
                    '🤮' => 1,
                    '🤢' => 2,
                    '😐' => 3,
                    '😃' => 4,
                    '😋' => 5,
                ],
                'expanded' => true,
                'choice_attr' => [
                    '🤮' => ['class' => 'm-2 '],
                    '🤢' => ['class' => 'm-2'],
                    '😐' => ['class' => 'm-2', 'checked' => 'checked'],
                    '😃' => ['class' => 'm-2'],
                    '😋' => ['class' => 'm-2'],
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    // 'class' => 'form-select'
                ],
                'label' => 'Noter la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4 '
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Noter la recette'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mark::class,
        ]);
    }
}