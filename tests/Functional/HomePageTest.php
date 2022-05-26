<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link= $crawler->selectLink("page d'inscription");
        $this->assertEquals(1, count($link));

        $recipes= $crawler->filter('.card');
        $this->assertEquals(3, count($recipes));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Sym6Recipe!');


    }
}
