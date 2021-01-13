<?php

namespace App\DataFixtures;

use App\Entity\Farmer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FarmerFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/farmers.csv";

        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data); $i++) {
            $line = $data[$i];
            $farmer = new Farmer();
            $farmer->setFarmSize($line['farm_size']);
            $farmer->setLastname($line['last_name']);
            $farmer->setFirstname($line['first_name']);
            $dates = explode("/", $line['registered_at']);
            $registeredAt = new \DateTime();
            $registeredAt->setDate($dates[2], $dates[0], $dates[1]);
            $farmer->setRegisteredAt($registeredAt);
            $cityId = $line['city_id'];
            $farmer->setCity($this->getReference('city_' . $cityId));
            $this->addReference('farmer_' . ($i+1), $farmer);
            $manager->persist($farmer);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CityFixtures::class];
    }
}
