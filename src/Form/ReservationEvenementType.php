<?php

namespace App\Form;

use App\Entity\ReservationEvenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationEvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbPlace')
            ->add('totale',HiddenType::class,[
                'attr'=>['name'=>'totale',
                    'id'=>'totale']])            
            ->add('modePaiement')
            ->add('idUser')
/*            ->add('evenement')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationEvenement::class,
        ]);
    }
}
