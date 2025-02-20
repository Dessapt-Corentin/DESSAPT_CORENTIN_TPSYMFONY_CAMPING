<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\Pricing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PricingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $pricing = $event->getData();
            $form = $event->getForm();

            if (!$pricing) {
                return;
            }

            $season = $pricing->getSeason();
            $seasonLabel = $season ? $season->getLabel() : '';

            $priceOptions = [
                'label' => 'Le prix ' . $seasonLabel . ':',
            ];
            

            // Si c'est la troisième saison (index 2), on cache le champ et on met la valeur à 0
            if ($season && $season->getId() == 3) {
                $priceOptions['attr'] = ['style' => 'display:none;'];
                $priceOptions['label_attr'] = ['style' => 'display:none;'];
                $priceOptions['data'] = 0;
            }

            $form->add('price', IntegerType::class, $priceOptions);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pricing::class,
        ]);
    }
}