<?php

namespace App\Controller;

use App\Repository\IngredientsRepository;
use App\Repository\UsersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class IngredientsController extends AbstractController
{
    #[Route('/ingredients', name: 'app_ingredients')]
    public function index(IngredientsRepository $repo): Response
    {

        // $user = $usersRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        // on n'a pas besoin de lui passer l'utilisateur actif, puisqu'on le récupère côté twig via la variable globale app.user

        return $this->render('ingredients/index.html.twig', [
            'ingredients' => $repo->findAll(),
            'controller_name' => 'Liste des Ingredients',
        ]);
    }
}
