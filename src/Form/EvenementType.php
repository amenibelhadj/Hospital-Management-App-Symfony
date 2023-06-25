<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie')
            ->add('titre')
            ->add('description')
            ->add('lieu')
            ->add('dateEvent',DateType::class,
                ['data'   => new \DateTime(),
                    'attr' => ['class'=> 'form-control js-datetimepicker','min' => ( new \DateTime() )->format('Y-m-d')],
                    'required' => false,
                    'widget'=> 'single_text',
                ])
            ->add('image', FileType::class, array('data_class' => null),[    'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,


            ])            ->add('tarif',)
            ->add('capacite')
            ->add('nbReservation')
            ->add('etat',HiddenType::class,[
                'attr'=>['name'=>'etat',
                    'id'=>'etat']])
            ->add('nbReservation',HiddenType::class,[
                'attr'=>['name'=>'nbReservation',
                    'id'=>'nbReservation']])
            ->add('pour')
            ->add('dateAjout',DateType::class,
                ['data'   => new \DateTime(),
                    'attr' => ['class'=> 'form-control js-datetimepicker','min' => ( new \DateTime() )->format('Y-m-d')],
                    'required' => false,
                    'widget'=> 'single_text',
                ])        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class
        ]);
    }
}
