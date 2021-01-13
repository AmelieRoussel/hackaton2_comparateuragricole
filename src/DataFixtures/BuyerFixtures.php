<?php

namespace App\DataFixtures;

use App\Entity\Buyer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BuyerFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/buyers.csv";

        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data); $i++) {
            $line = $data[$i];
            $buyer = new Buyer();
            $buyer->setName($line['name']);
            $buyer->setType($line['type']);
            $cityId = $line['city_id'];
            $buyer->setCity($this->getReference('city_' . $cityId));
            $this->addReference('buyer_' . ($i+1), $buyer);
            $manager->persist($buyer);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CityFixtures::class];
    }
}
