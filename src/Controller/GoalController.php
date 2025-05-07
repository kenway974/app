<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Goal;
use App\Entity\Task;
use App\Form\GoalType;
use App\Form\TaskType;
use App\Repository\GoalRepository;
use App\Repository\StatRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/goal')]
final class GoalController extends AbstractController
{
    #[Route(name: 'app_goal_index', methods: ['GET'])]
    public function index(GoalRepository $goalRepository): Response
    {
        return $this->render('goal/index.html.twig', [
            'goals' => $goalRepository->findAll(),
        ]);
    }



    #[Route('/{id}', name: 'app_goal_show', methods: ['GET'])]
    public function show(Goal $goal): Response
    {
        return $this->render('goal/show.html.twig', [
            'goal' => $goal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_goal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Goal $goal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GoalType::class, $goal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_goal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('goal/edit.html.twig', [
            'goal' => $goal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_goal_delete', methods: ['POST'])]
    public function delete(Request $request, Goal $goal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$goal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($goal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/new-task', name: 'app_goal_task_new', methods: ['GET', 'POST'])]
    public function newGoalTask(int $id, Request $request, EntityManagerInterface $entityManager, Security $security, GoalRepository $goalRepository, StatRepository $statRepository, CategoryRepository $categoryRepository): Response {
        // Récupérer le goal depuis son ID
        $goal = $goalRepository->find($id);
    
        if (!$goal) {
            throw $this->createNotFoundException('Objectif non trouvé.');
        }
    
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        // Optionnel : les stats liées à l’utilisateur (si utilisées dans le form)
        $stats = $statRepository->findByUserId($user->getId());
        
    
        // Nouvelle tâche
        $task = new Task();
        $task->setGoal($goal);
        $category = $goal->getCategory();
        $task->addCategory($category); // si Goal a une catégorie
        $task->setUser($user);
    
        // Création du formulaire
        $form = $this->createForm(TaskType::class, $task, [
            'stats' => $stats, // uniquement si tu utilises cette option dans TaskType
        ]);

        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_login', []); // redirection logique
        }
    
        return $this->render('task/goal_new.html.twig', [
            'task' => $task,
            'stats' => $stats,
            'form' => $form
        ]);
    }
}
