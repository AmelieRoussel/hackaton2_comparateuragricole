<?php

namespace App\Form;

use App\Controller\MapController;
use App\Repository\CityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterCitiesType extends AbstractType
{
    public $ListCities;

    public function __construct(CityRepository $cityRepository)
    {
        $cities = $cityRepository->findCitiesWithFarmers();
        foreach ($cities as $city) {
            $listCities[ucfirst(strtolower($city->getCity()))] = $city->getSlug();
        }
        $this->ListCities = $listCities;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Ville', ChoiceType::class, [
                'choices' => $this->ListCities,
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
            // Configure your form options here
        ]);
    }
}
