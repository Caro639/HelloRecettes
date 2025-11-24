<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security as SecurityAttribute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class IngredientController extends AbstractController
{
    /**
     * this function display all ingredients
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/ingredient', name: 'app_ingredient', methods: 'GET')]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        // $ingredients = $repository->findAll();
        $ingredients = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * this controller show a form which create ingredient
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/ingredient/nouveau', 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser());

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succès !'
            );
            return $this->redirectToRoute('app_ingredient');
        }
        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * ce controller crée un form pour modifier l ingredient
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(
        Ingredient $ingredient,
        Request $request,
        EntityManagerInterface $manager
    ): Response {

        if ($ingredient->getUser() !== $this->getUser()) {
            throw new AccessDeniedException('Vous ne pouvez pas modifier cet ingrédient.');
        }
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec succès !'
            );
            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * ce controler supprime un ingredient
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        if ($ingredient->getUser() !== $this->getUser()) {
            throw new AccessDeniedException('Vous ne pouvez pas supprimer cet ingrédient.');
        }

        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succés !'
        );
        return $this->redirectToRoute('app_ingredient');
    }
}