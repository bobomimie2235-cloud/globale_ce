<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\ProduitCategorie;
use App\Entity\Departement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class)
            ->add('intitule', TextType::class)
            ->add('description', TextType::class)
            ->add('departement', EntityType::class, [
                'class'        => Departement::class,
                'choice_label' => fn(Departement $d) => $d->getNumero() . ' - ' . $d->getNom(),
                'placeholder'  => '-- Sélectionner un département --',
                'required'     => false,
            ])
            ->add('prixPublic', TextType::class)
            ->add('prixUnitaire', TextType::class)
            ->add('stock', TextType::class)
            ->add('dateCreation', null, [
                'disabled' => true, // rend le champ non éditable
            ])
            ->add('produitCategorie', EntityType::class, [
                'class' => ProduitCategorie::class,
                'choice_label' => 'intitule',
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo du produit (PNG, JPG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2000k',
                        mimeTypes: ['image/*'],
                        mimeTypesMessage: 'Veuillez uploader une image valide',
                    )
                ],
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
