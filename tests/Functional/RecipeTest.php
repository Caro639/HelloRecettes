<?php

namespace App\Tests\Functional;

use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeTest extends WebTestCase
{
    public function testIfCreateRecipeIsSuccessful(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_recipe'));

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 16);

        // log in the user
        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('recipe.new'));

        //formulaire de création d'ingrédient
        $form = $crawler->filter('form[name=recipe]')->form([
            "recipe[name]" => "Recipe Test " . \uniqid(),
            "recipe[time]" => "33",
            "recipe[nbPeople]" => "6",
            "recipe[difficulty]" => "2",
            "recipe[description]" => "Description Test",
            "recipe[price]" => "33",
            "recipe[isFavorite]" => "1",
            "recipe[isPublic]" => "1",
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();
        //vérifier que l'ingrédient a été créé avec succès
        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Bravo ! Votre recette a été créée et partagée avec la communauté !'
        );

        $this->assertRouteSame('app_recipe');
    }

    public function testIfListRecipeIsSuccessful(): void
    {
        $client = static::createClient();

        // Accéder à la page des recettes
        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 16);

        // log in the user
        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('app_recipe'));

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('app_recipe');
    }

    public function testIfUpdateRecipeIsSuccessful(): void
    {
        $client = static::createClient();

        // Accéder à la page des recettes
        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 16);

        $recipe = $entityManager->getRepository(Recipe::class)->findOneBy([
            'user' => $user
        ]);

        // log in the user
        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('recipe.edit', [
                'id' => $recipe->getId()
            ])
        );

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name=recipe]')->form([
            "recipe[name]" => "Recipe Test Updated " . \uniqid(),
            "recipe[time]" => "45",
            "recipe[nbPeople]" => "4",
            "recipe[difficulty]" => "3",
            "recipe[description]" => "Description Test Updated",
            "recipe[price]" => "55",
            "recipe[isFavorite]" => "1",
            "recipe[isPublic]" => "1",
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Bravo ! Votre recette a été modifiée et est partagée avec la communauté !'
        );

        $this->assertRouteSame('app_recipe');
    }

    public function testIfDeleteRecipeIsSuccessful(): void
    {
        $client = static::createClient();

        // Accéder à la page des recettes
        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 16);

        $recipe = $entityManager->getRepository(Recipe::class)->findOneBy([
            'user' => $user
        ]);

        // log in the user
        $client->loginUser($user);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('recipe.delete', [
                'id' => $recipe->getId()
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Votre recette a été supprimée avec succès !'
        );

        $this->assertRouteSame('app_recipe');
    }

    public function testIfRecipePublicPageIsSuccessful(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 16);
        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('recipe.index.public'));

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('recipe.index.public');

        // Vérifier en base de données que toutes les recettes affichées sont publiques
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $publicRecipes = $entityManager->getRepository(Recipe::class)->findBy(['isPublic' => true]);

        // Vérifier qu'il y a au moins des recettes publiques
        $this->assertGreaterThan(0, \count($publicRecipes), 'Il devrait y avoir au moins une recette publique');

        // Compter le nombre de cartes de recettes affichées dans la page
        $recipeCards = $crawler->filter('.card');
        $displayedRecipesCount = $recipeCards->count();

        // Vérifier que le nombre de cartes affichées correspond au nombre de recettes publiques
        $this->assertEquals(
            \count($publicRecipes),
            $displayedRecipesCount,
            "Le nombre de recettes affichées ({$displayedRecipesCount}) devrait correspondre au nombre de recettes publiques en base (" . \count($publicRecipes) . ")"
        );

        // Vérifier que chaque recette affichée existe bien en base et est publique
        $recipeCards->each(function ($card) use ($entityManager) {
            // Récupérer l'ID de la recette depuis le header de la carte
            $cardHeader = $card->filter('.card-header')->text();
            // Extraire l'ID de la recette
            preg_match('/Recette n°(\d+)/', $cardHeader, $matches);
            $recipeId = isset($matches[1]) ? (int) $matches[1] : null;

            $this->assertNotNull($recipeId, "L'ID de la recette devrait être trouvé dans le header de la carte");

            // Chercher cette recette en base par son ID
            $recipe = $entityManager->getRepository(Recipe::class)->find($recipeId);

            // Vérifier que la recette existe
            $this->assertNotNull($recipe, "La recette avec l'ID {$recipeId} devrait exister en base de données");

            // Vérifier que la recette est bien publique
            $this->assertTrue($recipe->isIsPublic(), "La recette '{$recipe->getName()}' (ID: {$recipeId}) devrait être publique (isPublic = true)");
        });
    }
}

// $this->assertResponseIsSuccessful();
// $this->assertSelectorTextContains('h1', 'Hello World');