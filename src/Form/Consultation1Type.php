<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class Consultation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('maladie', ChoiceType::class, [
            'choices'  => [
                '' => '',
                'Sécheresse oculaire' => 'Sécheresse oculaire',
                'Dégénérescence maculaire' => 'Dégénérescence maculaire',
                'Glaucome' => 'Glaucome',
                'Cataracte' => 'Cataracte',
                'Troubles visuels/adolescent' => 'Troubles visuels/adolescent',
                'Les yeux: le miroir du corps' => 'Les yeux: le miroir du corps',
                'Brochure pour les patients' => 'Brochure pour les patients',
            ],
        ])
            ->add(
            'allergie', 
            ChoiceType::class, 
            [
                'choices' => [
                    'oui' => 'oui',
                    'non' => 'non',
                ],
            'expanded' => true
            ]
        )
            ->add('traitement')
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'format' => 'dd/MM/yyyy',
                'input' => 'string',
                'input_format' => 'Y-m-d'
            ])
            ->add('fiche')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
