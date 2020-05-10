<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADDRESS = 'address';

    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $address = new Address();

        $address
            ->setUser($this->getReference(UserFixtures::USER))
            ->setCity('Poznań')
            ->setAddress('ul. Testowa 42')
            ->setNip('9326150497')
            ->setPhone('123456789')
            ->setPostcode('60-681')
        ;

        $this->addReference(self::ADDRESS, $address);

        $manager->persist($address);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
