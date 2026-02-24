<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleCategorie;
use App\Entity\CouponReduction;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextType::class)
            ->add('infosActivite', TextType::class)
            ->add('siteWeb')
            ->add('imgLogo')
            ->add('imgPhotosDevanture')
            ->add('imgPhotosInterieur')
            ->add('offreCommerciale', TextType::class)
            ->add('horaires', TextType::class)
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'id',
            ])
            ->add('articleCategorie', EntityType::class, [
                'class' => ArticleCategorie::class,
                'choice_label' => 'id',
            ])
            ->add('couponReduction', EntityType::class, [
                'class' => CouponReduction::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
