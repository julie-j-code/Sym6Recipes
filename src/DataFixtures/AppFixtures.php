<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator;

class AppFixtures extends Fixture
{

    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance


        for ($i = 0; $i < 49; $i++) {

            // $faker = new Factory::create('fr_FR');
            # code...
            $ingredient = new Ingredients();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(1, 199));

            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
