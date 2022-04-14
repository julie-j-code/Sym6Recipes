<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsType;
use App\Repository\IngredientsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


// puisque dans ma version, seul l'administrateur (distributeur) a la main sur la base commune des ingrédients

    #[Route('/ingredients/new', name: 'ingredient_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function new(Request $request, IngredientsRepository $ingredientsRepository): Response
    {
        $ingredient = new Ingredients();
        $form = $this->createForm(IngredientsType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientsRepository->add($ingredient);
            $this->addFlash(
               'success',
               'L\'ingrédient a bien été ajouté'
            );
            return $this->redirectToRoute('ingredients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredients/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/ingredients/{id}/edit', name: 'ingredient_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Ingredients $ingredient, IngredientsRepository $ingredientsRepository): Response
    {
        $form = $this->createForm(IngredientsType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientsRepository->add($ingredient);
            $this->addFlash(
               'success',
               'L\'ingrédient a  bien été modifié'
            );
            return $this->redirectToRoute('ingredients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ingredients/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/ingredients/{id}/delete', name: 'ingredient_delete', methods: ['POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(Request $request, Ingredients $ingredient, IngredientsRepository $ingredientsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ingredient->getId(), $request->request->get('_token'))) {
            $ingredientsRepository->remove($ingredient);
        }

        return $this->redirectToRoute('ingredients_index', [], Response::HTTP_SEE_OTHER);
    }


}
