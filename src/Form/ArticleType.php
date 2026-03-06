<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Departement;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('infosActivite', TextareaType::class, [
                'label' => 'Infos activité',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('siteWeb', TextType::class, [
                'label' => 'Site web',
                'required' => false,
            ])
            ->add('imgLogo', FileType::class, [
                'label' => 'Logo de l\'article (PNG, JPG)',
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
            ->add('imgPhotosDevanture', FileType::class, [
                'label' => 'Photo devanture (PNG, JPG)',
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
            ->add('imgPhotosInterieur', FileType::class, [
                'label' => 'Photo intérieur (PNG, JPG)',
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
            ->add('departement', EntityType::class, [
                'class'        => Departement::class,
                'choice_label' => fn(Departement $d) => $d->getNumero() . ' - ' . $d->getNom(),
                'placeholder'  => '-- Sélectionner un département --',
                'required'     => false,
            ])
            ->add('offre', TextType::class, [
                'label' => 'Offre (ex: -5%, -25%...)',
                'required' => false,
            ])
            ->add('offreCommerciale', TextareaType::class, [
                'label' => 'Offre commerciale',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('horaires', TextareaType::class, [
                'label' => 'Horaires',
                'required' => false,
                'empty_data' => '',
                'attr' => ['rows' => 4],
            ])
            ->add('articleCategorie', EntityType::class, [
                'class' => ArticleCategorie::class,
                'choice_label' => 'intitule',
                'required' => false,
                'placeholder' => '-- Choisir une catégorie --',
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
