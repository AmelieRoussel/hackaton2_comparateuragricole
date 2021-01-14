<?php

namespace App\Form;

use App\Repository\CityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterDepartmentsType extends AbstractType
{
    public $listDepartments;

    public function __construct(CityRepository $cityRepository)
    {
        $cities = $cityRepository->findDepartmentsWithFarmers();
        foreach ($cities as $city) {
            $listDepartments[ucfirst(strtolower($city->getDepartment()))] = $city->getDepartment();
        }
        $this->listDepartments = $listDepartments;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Departement', ChoiceType::class, [
                'label' => 'Département',
                'choices' => $this->listDepartments,
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'empty_data' => 'none',
                'placeholder' => 'Tous',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
