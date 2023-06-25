<?php

namespace App\Form;

use App\Entity\Achat;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix')
            ->add('quantite')
         // ->add('user')
          // ->add('produit')
            ->add('produit',EntityType::class,
                ['class'=>Produit::class,
                    'choice_label'=>'nom'
                    ,
                    'multiple'=>false])
        
      

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Achat::class,
        ]);
    }
}
