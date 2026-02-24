<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\ProduitCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class)
            ->add('intitule', TextType::class)
            ->add('description', TextType::class)
            ->add('prixUnitaire', TextType::class)
            ->add('stock', TextType::class)
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
