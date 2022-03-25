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

    #[Route('/favoris', name: 'show_favoris', methods: ['GET'])]
    public function favoris(RecipesRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        // $user = $paginator->paginate(
        //     $data, // Requête contenant les données à paginer (ici nos articles)
        //     $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        //     8 // Nombre de résultats par page
        // );
        $recipes = $repo->findAll();
        return $this->render('users/users_favoris.html.twig', [
            // on récupèrera l'utilisateur actif
            'user' => $user,
            'recipes'=> $recipes,
            'controller_name' => 'Vos recettes favorites',
        ]);
    }
}
