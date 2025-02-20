<?php

namespace App\Form;

use App\Entity\Accommodation;
use App\Entity\Equipment;
use App\Entity\TypeAccommodation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Pricing;
use App\Form\PricingType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AccommodationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom de l\'hébergement:',
            ])
            ->add('location_number', TextType::class, [
                'label' => 'Numéro d\'emplacement:',
            ])
            ->add('size', IntegerType::class, [
                'label' => 'Taille (m²):',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
            ])
            ->add('capacity', IntegerType::class, [
                'label' => 'Capacité d\'accueil:',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image:',
                'mapped' => false,
                'required' => false,
            ])
            ->add('availability', CheckboxType::class, [
                'label' => 'Disponible:',
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => TypeAccommodation::class,
                'choice_label' => 'label',
                'label' => 'Type d\'hébergement:',
            ])
            ->add('pricings', CollectionType::class, [
                'entry_type' => PricingType::class,
                'entry_options' => ['label' => false], // Supprime les labels des champs enfants
                'label' => false, // Supprime le label global
            ])
            ->add('equipments', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Équipements disponibles:',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accommodation::class,
        ]);
    }
}
