<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsType;
use App\Repository\IngredientsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredients',  name: 'ingredients_')]
class IngredientsController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(IngredientsRepository $ingredientsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data= $ingredientsRepository->findAll();
        
        $ingredients = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        
        return $this->render('ingredients/index.html.twig', [
            'ingredients' => $ingredients,
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

   
    #[Route('/{id}', name: 'details', methods: ['GET'])]
    public function show(Ingredients $ingredient): Response
    {
        return $this->render('ingredients/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

   
}
