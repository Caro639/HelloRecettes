<?php

namespace App\Tests\Functional\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ContactTest extends WebTestCase
{
    public function testIfContactPageIsSuccessful(): void
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(User::class)->findOneBy([
            'id' => 16
        ]);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/admin');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Administration du site');
    }
}