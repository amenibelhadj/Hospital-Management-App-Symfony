<?php

namespace App\Form;

use App\Entity\Disponibilite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\DateType;



class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_medd', ChoiceType::class, [
            'choices'  => [
                
                'Kousay' => 'Kousay',
                'Mohamed' => 'Mohamed',
                'Ameni' => 'Ameni',
                'Khairy' => 'Khairy',
            ],
            'label'=>'nom medecin '
        ]) 
            
        ->add('date')
       /*->add('date', DateType::class, [
            'widget' => 'choice',
        ])*/
        
            /*->add('date', ChoiceType::class,[
                'choices' => [
                    'Lundi' =>'Lundi',
                    'Mardi' =>'Mardi',
                    'Mercredi' =>'Mercredi',
                    'Jeudi'=>'Jeudi',
                    'Vendredi'=>'Vendredi',
                ],
                
                ])*/

            ->add('heure', ChoiceType::class, [
                'choices'  => [
                    
                    '09:00' => '09:00',
                    '10:00' => '10:00',
                    '11:00' => '11:00',
                    '13:00' => '13:00',
                ],
            ]) 
            
        
          # ->add('nom_medd',EntityType::class,['class'=>Disponibilite::class,'choice_label'=>'nom_medd'])
      
          
           #->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
