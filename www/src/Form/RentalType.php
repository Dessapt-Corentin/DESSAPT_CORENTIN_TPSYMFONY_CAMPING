<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Rental;
use App\Entity\Accommodation;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RentalType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
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
            ]);

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('accommodation', EntityType::class, [
                'class' => Accommodation::class,
                'choice_label' => 'id',
                'label' => 'Accommodation ID:'
            ]);
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'label' => 'User ID:'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
