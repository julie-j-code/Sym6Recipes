<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactFormTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Nous contacter');

        // récupérer le formulaire

        $submitButton = $crawler->selectButton("Soumettre ma demande");
        $form = $submitButton->form();

        $form["contact[fullName]"] = "Jean Dupont";
        $form["contact[email]"] = "jd@symrecipe.com";
        $form["contact[subject]"] = "Test";
        $form["contact[message]"] = "Test";

        // soumettre le formulaire
        $client->submit($form);

        // vérifier le statut http (redirection)
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Vérifier l'envoie du mail
        $this->assertEmailCount(1);
        $client->followRedirect();

        // vérifier la présence du message de succès
        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Votre demande a été envoyée avec succès !'
        );



    }
}
