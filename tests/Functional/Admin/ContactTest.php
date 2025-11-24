<?php

namespace App\Tests\Functional\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ContactTest extends WebTestCase
{
    public function testIfAdminPageIsSuccessful(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(User::class)->findOneBy([
            'id' => 16
        ]);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate('admin'));

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', "Bienvenue au sein de l'administration de HelloRecettes");

        $this->assertRouteSame('admin');
    }

    public function testIfAdminPageIsNotAccessibleForUser(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(User::class)->findOneBy([
            'id' => 17
        ]);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate('admin'));

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCrudIsHere(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(User::class)->findOneBy([
            'id' => 16
        ]);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate('admin'));


        $this->assertResponseIsSuccessful();

        $crawler = $client->clickLink('Contacts');

        $this->assertResponseIsSuccessful();

        $client->click($crawler->filter('.action-new')->link());

        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, $urlGenerator->generate('admin'));

        $client->click($crawler->filter('.action-edit')->link());

        $this->assertResponseIsSuccessful();
    }
}