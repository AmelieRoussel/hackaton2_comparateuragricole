<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CityFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;

    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/cities.csv";

        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data); $i++) {
            $line = $data[$i];
            $city = new City();
            $city->setZipcode($line['zipcode']);
            $zipcode = $line['zipcode'];
            $splitZipcode = str_split($zipcode, 2);
            $city->setDepartment($splitZipcode[0]);
            $city->setCity($line['city']);
            $slug = $this->slugify->generate($city->getCity());
            $city->setSlug($slug);
            $city->setLatitude($line['lat']);
            $city->setLongitude($line['long']);
            $city->setInseeCode($line['insee_code']);
            $this->addReference('city_' . ($i+1), $city);
            $manager->persist($city);
        }

        $manager->flush();
    }
}
