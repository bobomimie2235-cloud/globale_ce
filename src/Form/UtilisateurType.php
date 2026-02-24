<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurGroupe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateInscription', null, [
                'disabled' => true, // rend le champ non éditable
            ])
            ->add('utilisateurGroupe', EntityType::class, [
                'class' => \App\Entity\UtilisateurGroupe::class,
                'choice_label' => 'nomGroupe',
                'disabled' => true, // Groupe non modifiable
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
