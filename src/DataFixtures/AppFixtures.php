<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use App\Entity\Users;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class AppFixtures extends Fixture
{

    // private Generator $faker;
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance


        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPassword(
                    $this->passwordEncoder->hashPassword($user, 'secret')
                );

            $users[] = $user;
            $manager->persist($user);
        }



        for ($i = 0; $i < 49; $i++) {

            // $faker = new Factory::create('fr_FR');
            # code...
            $ingredient = new Ingredients();
            $ingredient->setName($faker->word())
                ->setPrice(mt_rand(1, 199));
                $ingredient->addUser($users[mt_rand(0, count($users) - 1)]);

            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
