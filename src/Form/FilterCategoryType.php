<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterCategoryType extends AbstractType
{

    private const CATEGORIES = [
        'Feverol' => 'feverol',
        'Tournesol' => 'tournesol',
        'Blé' => 'ble',
        'Avoine' => 'avoine',
        'Triticale' => 'triticale',
        'Orge' => 'orge',
        'Maïs' => 'mais',
        'Pois' => 'pois',
        'Colza' => 'colza',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => self::CATEGORIES,
                'multiple' => false,
                'expanded' => false,
                'required'   => false,
                'empty_data' => 'none',
                'placeholder' => 'Toutes',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Transaction::class,
        ]);
    }
}
