<?php

namespace App\Controller;

use SearchType;
use App\Model\SearchData;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(
        RecipeRepository $recipeRepository,
        Request $request
    ): Response {
        // Vérifier si un message de bienvenue est en attente
        if ($request->hasSession()) {
            $session = $request->getSession();
            if ($fullName = $session->get('_login_welcome_message')) {
                $this->addFlash('success', "Bonjour $fullName ! Bienvenue sur HelloRecettes !");
                $session->remove('_login_welcome_message');
            }
        }

        return $this->render('pages/home.html.twig', [
            'recipes' => $recipeRepository->findPublicRecipe(3)
        ]);
    }
}