<?php

namespace App\Form;

use App\Entity\UtilisateurGroupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UtilisateurGroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomGroupe', TextType::class)
            ->add('referenceGroupe', TextType::class)
            ->add('adresse', TextType::class)
            ->add('codePostal', TextType::class)
            ->add('ville', TextType::class)
            ->add('telephone', TextType::class)
            ->add('email', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UtilisateurGroupe::class,
        ]);
    }
}
