<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsType;
use App\Repository\IngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredients',  name: 'ingredients_')]
class IngredientsController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(IngredientsRepository $ingredientsRepository): Response
    {
        return $this->render('ingredients/index.html.twig', [
            'ingredients' => $ingredientsRepository->findAll(),
            'controller_name' => 'Liste des ingrédients'
        ]);
    }

    #[Route('/user', name: 'user', methods: ['GET'])]
    public function user(IngredientsRepository $ingredientsRepository): Response
    {
        // $user = $usersRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        // on n'a pas besoin de lui passer l'utilisateur actif, puisqu'on le récupère côté twig via la variable globale app.user



        return $this->render('ingredients/index_user.html.twig', [
            'ingredients' => $ingredientsRepository->findAll(),
            'controller_name' => 'Liste des ingrédients'
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, IngredientsRepository $ingredientsRepository): Response
    {
        $ingredient = new Ingredients();
        $form = $this->createForm(IngredientsType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientsRepository->add($ingredient);
            return $this->redirectToRoute('ingredients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredients/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'details', methods: ['GET'])]
    public function show(Ingredients $ingredient): Response
    {
        return $this->render('ingredients/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredients $ingredient, IngredientsRepository $ingredientsRepository): Response
    {
        $form = $this->createForm(IngredientsType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientsRepository->add($ingredient);
            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredients/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Ingredients $ingredient, IngredientsRepository $ingredientsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ingredient->getId(), $request->request->get('_token'))) {
            $ingredientsRepository->remove($ingredient);
        }

        return $this->redirectToRoute('app_ingredients_index', [], Response::HTTP_SEE_OTHER);
    }
}
