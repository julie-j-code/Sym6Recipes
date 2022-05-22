<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Marks;
use App\Entity\Recipe;
use App\Entity\Recipes;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    public function getEntity(): Recipes
    {
        return (new Recipes())
            ->setName('Recipe #1')
            ->setDescription('Description #1')
            ->setIsPublic(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();

        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(0, $errors);
    }

    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();
        $recipe->setName('');

        $errors = $container->get('validator')->validate($recipe);
        $this->assertCount(1, $errors);
    }

    public function testGetAverage()
    {
        $recipe = $this->getEntity();
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(Users::class, 1);

        for ($i = 0; $i < 5; $i++) {
            $mark = new Marks();
            $mark->setMark(2)
                ->setUser($user)
                ->setRecipe($recipe);

            $recipe->addMark($mark);
        }

        $this->assertTrue(2.0 === $recipe->getAverage());
    }
}
