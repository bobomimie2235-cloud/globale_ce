<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\ProduitCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference')
            ->add('intitule')
            ->add('description')
            ->add('prixUnitaire')
            ->add('stock')
            ->add('dateCreation')
            ->add('produitCategorie', EntityType::class, [
                'class' => ProduitCategorie::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
