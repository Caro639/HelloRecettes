<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        $client = static::createClient();

        // Accéder à la page de connexion
        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request('GET', $urlGenerator->generate('app_security'));


        //formulaire de connexion
        $form = $crawler->filter('form[name=login]')->form([
            "_username" => "contact@hellorecettes.fr",
            "_password" => "password"
        ]);
        //soumettre le formulaire
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //suivre la redirection
        $client->followRedirect();

        //vérifier que l'utilisateur est authentifié et redirigé
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur HelloRecettes !');
    }
}
