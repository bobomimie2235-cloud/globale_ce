<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\CouponReduction;
use App\Entity\CouponCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CouponReductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class)
            ->add('intitule', TextType::class)
            ->add('ville', TextType::class)
            ->add('adresse', TextType::class)
            ->add('offreCommerciale', TextType::class)
            ->add('logo', FileType::class, [
                'label' => 'Logo du Coupon Réduction (PNG, JPG)',
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
            ->add('offre', TextType::class, [
                'label' => 'Offre (ex: -5%, -25%...)',
                'required' => false,
            ])
            ->add('actif', CheckboxType::class, [
                'label' => 'Coupon actif',
                'required' => false,
            ])
            ->add('couponCategorie', EntityType::class, [
                'class' => CouponCategorie::class,
                'choice_label' => 'categorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CouponReduction::class,
        ]);
    }
}
