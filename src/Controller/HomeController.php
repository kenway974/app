<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Stat;
use App\Entity\Category;
use App\Entity\Goal;
use App\Entity\Task;
use App\Repository\StatRepository;
use App\Repository\GoalRepository;
use App\Repository\CategoryRepository;
use App\Repository\TaskRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security, TaskRepository $taskRepository, StatRepository $statRepository, CategoryRepository $categoryRepository, GoalRepository $goalRepository): Response
    {
        $user = $security->getUser();

        if(!$user) {
            return $this->render('home/home.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }
        
        $userId = $user->getId();
        $goals = $goalRepository->findBy(['user' => $user]);
        $stats = $statRepository->findBy(['user' => $user]);
        $tasks = $taskRepository->findBy(['user' => $user]);
        $categories = $categoryRepository->findAll();

        /*dump($goals);
        dump($tasks);
        dump($stats);
        dump($categories);
        dd($user);*/


        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'tasks' => $tasks,
            'stats' => $stats,
            'goals' => $goals,
            'categories' => $categories,
        ]);
    }
}
