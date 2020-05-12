<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public const PRODUCT = 'product';

    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 50; $i++) {
            $product = new Product();

            $product
                ->setName('Produkt ' . $i)
                ->setPrice($this->faker->randomFloat(2, 10, 10000))
                ->setQuantity($this->faker->numberBetween(10, 100))
                ;

            $this->addReference(self::PRODUCT . $i, $product);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
