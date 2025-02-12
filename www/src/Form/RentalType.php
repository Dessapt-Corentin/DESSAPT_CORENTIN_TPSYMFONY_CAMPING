<?php

namespace App\Form;

use App\Entity\Rental;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adult_number', IntegerType::class, [
                'label' => 'Nombre d\'adultes: ',
            ])
            ->add('child_number', IntegerType::class, [
                'label' => 'Nombre d\'enfants: ',
            ])
            ->add('date_start', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début: ',
            ])
            ->add('date_end', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin: ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
