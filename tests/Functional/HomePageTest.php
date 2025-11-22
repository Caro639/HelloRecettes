<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $button = $crawler->filter('a.btn.btn-warning.btn-lg:contains("Inscription")');
        $this->assertEquals(1, $button->count());

        $recipes = $crawler->filter('.recipes .card');
        $this->assertEquals(3, $recipes->count());

        $this->assertSelectorTextContains('h1', 'Bienvenue sur HelloRecettes !');
    }
}
