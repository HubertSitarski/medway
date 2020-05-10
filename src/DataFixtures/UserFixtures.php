<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public const USER = 'user';

    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user
            ->setEmail('test@test.com')
            ->setPassword('Abcd@123')
        ;

        $this->addReference(self::USER, $user);

        $manager->persist($user);
        $manager->flush();
    }
}
