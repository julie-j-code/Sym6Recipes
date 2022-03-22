<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use App\Entity\Recipes;
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
            $ingredients = [];

            // $faker = new Factory::create('fr_FR');
            # code...
            $ingredient = new Ingredients();
            $ingredient->setName($faker->word())
                ->setPrice(mt_rand(1, 199));
                // $ingredient->addUser($users[mt_rand(0, count($users) - 1)]);
                $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Recipes
        $recipes = [];
        for ($j = 0; $j < 25; $j++) {
            $recipe = new Recipes();
            $recipe->setName($faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setDescription($faker->text(300))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)    
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ;

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $recipes[] = $recipe;
            $manager->persist($recipe);
        }


        


        $manager->flush();
    }
}
