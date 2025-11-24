<?php

namespace App\Tests\Functional;

use App\Entity\Ingredient;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class IngredientTest extends WebTestCase
{
    public function testIfCreateIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        // Accéder à la page des ingrédients
        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request('GET', $urlGenerator->generate('app_ingredient'));


        // need entity manager to fetch a user and log in
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 16); // assuming user with ID 1 exists

        // log in the user
        $client->loginUser($user);

        // Accéder à la page de création d'ingrédient
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient.new'));

        //formulaire de création d'ingrédient
        $form = $crawler->filter('form[name=ingredient]')->form([
            "ingredient[name]" => "Ingredient Test " . \uniqid(),
            "ingredient[price]" => \floatval(33),
        ]);

        $client->submit($form);

        // vérifier le statut HTTP 302
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //suivre la redirection
        $client->followRedirect();
        //vérifier que l'ingrédient a été créé avec succès
        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Votre ingrédient a été créé avec succès !'
        );

        $this->assertRouteSame('app_ingredient');
    }

    public function testIfListIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        // Accéder à la page des ingrédients
        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request('GET', $urlGenerator->generate('app_ingredient'));

        // need entity manager to fetch a user and log in
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 16);

        // log in the user
        $client->loginUser($user);

        // Accéder à la page des ingrédients
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('app_ingredient'));

        // vérifier si response is successful
        $this->assertResponseIsSuccessful();

        // vérifier la route
        $this->assertRouteSame('app_ingredient');

    }

    public function testIfUpdateIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        // Accéder à la page des ingrédients
        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 16);

        $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy([
            'user' => $user
        ]);

        // log in the user
        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate(
                'ingredient.edit',
                ['id' => $ingredient->getId()]
            )
        );

        $this->assertResponseIsSuccessful();

        //formulaire de modification d'ingrédient
        $form = $crawler->filter('form[name=ingredient]')->form([
            "ingredient[name]" => "Ingredient Modifié " . \uniqid(),
            "ingredient[price]" => \floatval(55),
        ]);

        $client->submit($form);

        // vérifier le statut HTTP 302
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Votre ingrédient a été modifié avec succès !'
        );

        $this->assertRouteSame('app_ingredient');

    }
}