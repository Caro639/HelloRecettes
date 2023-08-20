<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiRecipesController extends AbstractController
{

    /**
     * API json affiche la liste des recettes
     *
     * @param RecipeRepository $repository
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */
    #[Route('/api/recipes', name: 'api.recipes', methods: ['GET'])]
    public function indexApi(
        RecipeRepository $repository,
        SerializerInterface $serializerInterface
    ): JsonResponse {
        $recipeList = $repository->findPublicRecipe(null);

        $jsonRecipeList = $serializerInterface->serialize($recipeList, 'json', ['groups' => 'getRecipes']);
        return new JsonResponse($jsonRecipeList, Response::HTTP_OK, [], true);
    }


    /**
     * Undocumented function
     *
     * @param RecipeRepository $repository
     * @param Recipe $recipe
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */
    #[Route('/api/recipes/{id}', name: 'api.recipe', methods: ['GET'])]
    public function getRecipe(RecipeRepository $repository, Recipe $recipe, SerializerInterface $serializerInterface): JsonResponse
    {
        $recipe = $repository->findBy(['id' => $recipe->getId()]);

        $jsonRecipe = $serializerInterface->serialize($recipe, 'json', ['groups' => 'getRecipes']);
        return new JsonResponse($jsonRecipe, Response::HTTP_OK, [], true);
    }
}