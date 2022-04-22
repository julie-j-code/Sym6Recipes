<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\RecipesRepository;
use App\Repository\UsersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users', name: 'users_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    
    public function index(UsersRepository $repo): Response
    {
        $users = $repo->findAll();
        
        return $this->render('users/index.html.twig', [
            'users' => $users,
            'controller_name' => 'Liste de tous les utilisateurs',
        ]);
    }

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function profile(UsersRepository $repo, RecipesRepository $recipesRepository): Response
    {
        // $ingredients=$recipesRepository->find('ingredients');
        // $userRecipes=$repo->find('recipes');
        
        return $this->render('users/profile.html.twig', [
            // on récupèrera l'utilisateur actif
            'controller_name' => 'Votre profilN                                                                                               utilisateur',
        ]);
    }

    #[Route('/favoris', name: 'show_favoris', methods: ['GET'])]
    public function favoris(RecipesRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $recipes = $repo->findAll();
        return $this->render('users/users_favoris.html.twig', [
            // on récupèrera l'utilisateur actif
            'user' => $user,
            'recipes'=> $recipes,
            'controller_name' => 'Vos recettes favorites',
        ]);
    }
}
