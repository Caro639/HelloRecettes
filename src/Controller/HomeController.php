<?php

namespace App\Controller;

use SearchType;
use App\Model\SearchData;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(
        RecipeRepository $recipeRepository
    ): Response {

        return $this->render('pages/home.html.twig', [

            'recipes' => $recipeRepository->findPublicRecipe(3)
        ]);
    }
}