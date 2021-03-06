<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredients;
use App\Entity\Marks;
use App\Entity\Recipes;
use App\Entity\Users;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    // private Generator $faker;
    // public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    // {
    // }

    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance


        $faker = Faker\Factory::create('fr_FR');

        $admin = new Users();
        $admin->setFullName('Administrateur de SymRecipe')
            ->setPseudo(null)
            ->SetEmail('jeannet.julie@gmail.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $users[] = $admin;
        $manager->persist($admin);


        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setFullName(($faker->firstName() . ' ' . $faker->lastName()))
                ->setPseudo($faker->word())
                // ->setPassword(
                //     $this->passwordEncoder->hashPassword($user, 'secret')
                // );
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }



        // Ingredients
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredients();
            $ingredient->setName($faker->word())
                ->setPrice(mt_rand(0, 100));

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
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false)
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addFavorite($users[mt_rand(0, count($users) - 1)]);
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $recipes[] = $recipe;

            $manager->persist($recipe);
        }

        // Marks
        foreach ($recipes as $recipe) {
            for ($i = 0; $i < mt_rand(0, 4); $i++) {
                # code...
                $mark = new Marks();
                $mark->setMark(mt_rand(1, 5))
                    ->setUser($users[mt_rand(0, count($users) - 1)])
                    ->setRecipe($recipe);

                $manager->persist($mark);
            }
        }

        // Contact
        for ($i = 0; $i < 5; $i++) {
            $contact = new Contact();
            $contact->setFullName($faker->name())
                ->setEmail($faker->email())
                ->setSubject('Demande n??' . ($i + 1))
                ->setMessage($faker->text());

            $manager->persist($contact);
        }


        $manager->flush();
    }
}
