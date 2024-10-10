<?php

namespace App\Controller;

use App\Entity\Recipe;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use ApiPlatform\Api\UrlGeneratorInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiRecipesController extends AbstractController
{

    /**
     * API json affiche la liste des recettes
     *
     * @param RecipeRepository $repository
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */
    #[IsGranted('ROLE_USER')]
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
     * Affiche une recette par id
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

    /**
     * supprimer recette
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Entity\Recipe $recipe
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Security("is_granted('ROLE_USER') and recipe.getIsPublic() === true || user === recipe.getUser()")]
    #[Route('/api/recipes/{id}', name: 'api.recipe.delete', methods: ['DELETE'])]
    public function deleteRecipe(EntityManagerInterface $manager, Recipe $recipe): JsonResponse
    {
        $manager->remove($recipe);
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Summary of createRecipe
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializerInterface
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \ApiPlatform\Api\UrlGeneratorInterface $urlGeneratorInterface
     * @param \App\Repository\UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/api/recipes', name: 'api.recipe.create', methods: ['POST'])]
    public function createRecipe(
        Request $request, SerializerInterface $serializerInterface,
        EntityManagerInterface $manager, UrlGeneratorInterface $urlGeneratorInterface,
        UserRepository $userRepository
    ): JsonResponse {
        $recipe = $serializerInterface->deserialize($request->getcontent(), Recipe::class, 'json');

        //recup ens des donnees
        $content = $request->toArray();

        //recup id user sil n'y en a pas on met -1 par defaut
        $user = $content['id'] ?? -1;

        // on cherche le user et assigne id
        // si find ne trouve pas null est retourne
        $recipe->setUser($userRepository->find($user));

        $manager->persist($recipe);
        $manager->flush();

        $jsonRecipe = $serializerInterface->serialize($recipe, 'json', ['groups' => 'getRecipes']);

        $location = $urlGeneratorInterface->generate(
            'detailRecipe',
            ['id' => $recipe->getId()],
            UrlGeneratorInterface::ABS_URL
        );

        return new JsonResponse($jsonRecipe, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * Summary of updateRecipe
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializerInterface
     * @param \App\Entity\Recipe $currentRecipe
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Repository\UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Security("is_granted('ROLE_USER') and recipe.getIsPublic() === true || user === recipe.getUser()")]
    #[Route('/api/recipes/{id}', name: 'api.recipe.update', methods: ['PUT'])]
    public function updateRecipe(
        Request $request, SerializerInterface $serializerInterface,
        Recipe $currentRecipe, EntityManagerInterface $manager, UserRepository $userRepository
    ): JsonResponse {
        $updateRecipe = $serializerInterface->deserialize(
            $request->getContent(),
            Recipe::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentRecipe]
        );
        $content = $request->toArray();
        $user = $content['id'] ?? -1;
        $updateRecipe->setUser($userRepository->find($user));

        $manager->persist($updateRecipe);
        $manager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}