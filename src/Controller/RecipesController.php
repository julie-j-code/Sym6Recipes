<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Form\RecipesType;
use App\Repository\IngredientsRepository;
use App\Repository\RecipesRepository;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipes', name: 'recipes_')]
class RecipesController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(RecipesRepository $recipesRepository, IngredientsRepository $ingredientsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $data = $recipesRepository->findAll();
        
        $recipes = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        
        return $this->render('recipes/index.html.twig', [
            'ingredients' => $ingredientsRepository->findAll(),
            'recipes' => $recipes,
            'controller_name'=>"Ensemble des recettes enregistrées"
        ]);
    }

    #[Route('/public', name: '_public', methods: ['GET'])]
    public function indexPublic(RecipesRepository $recipesRepository, PaginatorInterface $paginator, Request $request): Response
    {
    
        // $recipes=$recipesRepository->findByIsPublic('1');
        $data=$recipesRepository->findPublicRecipes();

        $recipes = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            8 // Nombre de résultats par page
        );

        return $this->render('recipes/index_public.html.twig', [
            'recipes' => $recipes,
            'controller_name'=>"Recettes publiques"

        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecipesRepository $recipesRepository): Response
    {
        $recipe = new Recipes();
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            
   
            $recipesRepository->add($recipe);
            return $this->redirectToRoute('recipes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipes/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
            'controller_name'=>"Création d'une recette"
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Recipes $recipe): Response
    {
        return $this->render('recipes/show.html.twig', [
            'recipe' => $recipe,
            'controller_name'=>"Détail de la recette"
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipes $recipe, RecipesRepository $recipesRepository): Response
    {
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipesRepository->add($recipe);
            return $this->redirectToRoute('recipes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
            'controller_name'=>"Edition d'une recette"
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Recipes $recipe, RecipesRepository $recipesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $recipesRepository->remove($recipe);
        }

        return $this->redirectToRoute('recipes_index', [], Response::HTTP_SEE_OTHER);
    }
}
