<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testIfSubmitContactFormIsSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        // soumission du formulaire
        $form = $crawler->selectButton('Validez')->form();
        $form['contact[fullName]'] = 'John Doe';
        $form['contact[email]'] = 'john.doe@example.com';
        $form['contact[subject]'] = 'Sujet de test';
        $form['contact[message]'] = 'Ceci est un message de test.';

        // soumettre le formulaire
        $client->submit($form);

        // vérifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // vérifier envoi du mail
        $this->assertEmailCount(1);

        $client->followRedirect();

        // vérifier le message flash
        $this->assertSelectorTextContains(
            'div.alert.alert-success.mt-4',
            'Votre message a été envoyé avec succès !'
        );
    }
}
