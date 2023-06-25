<?php

namespace App\Form;

use App\Entity\Fiche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Fiche1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, array(
            'label' => 'Vorname ',
            'attr' => array(
                'placeholder' => 'saisir votre nom'
            )
       ))
       ->add('prenom', TextType::class, array(
        'label' => 'Vorname ',
        'attr' => array(
            'placeholder' => 'saisir votre prenom'
        )
   ))
       ->add('age', TextType::class, array(
    'label' => 'Vorname ',
    'attr' => array(
        'placeholder' => 'saisir votre age'
    )
   ))
     ->add('poids', TextType::class, array(
    'label' => 'Vorname ',
    'attr' => array(
        'placeholder' => 'expl : 85 Kg'
    )
))
     ->add('taille', TextType::class, array(
    'label' => 'Vorname ',
    'attr' => array(
        'placeholder' => 'expl : 1,76 m'
    )
))
    ->add('email', TextType::class, array(
    'label' => 'Vorname ',
    'attr' => array(
        'placeholder' => 'email@exemple.tn'
    )
))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class,
        ]);
    }
}
