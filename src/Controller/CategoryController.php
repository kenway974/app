<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Goal;
use App\Entity\Task;
use App\Form\CategoryType;
use App\Form\CategoryGoalType;
use App\Form\GoalType;
use App\Form\TaskType;
use App\Repository\CategoryRepository;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/category')]

final class CategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new-category', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Security $security, EntityManagerInterface $entityManager, StatRepository $statRepository): Response
    {
        $user = $security->getUser();
        dump($user);

        $userId = $user->getId();
        dd($userId);

        $stats = $statRepository->findByUserId($userId);
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, ['stats' => $stats]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'stats' => $stats,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{categoryId}/new-task', name: 'app_category_task_new', methods: ['GET', 'POST'])]
    public function newCategoryTask(int $categoryId,Request $request,EntityManagerInterface $entityManager,Security $security,CategoryRepository $categoryRepository,StatRepository $statRepository): Response {
        // Récupérer la catégorie depuis son ID
        $category = $categoryRepository->find($categoryId);
    
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }
    
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
    
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        // Récupérer les stats liées à la catégorie (ManyToMany)
        $stats = $statRepository->findByCategory($category);
    
        // Créer une nouvelle tâche
        $task = new Task();
        $task->addCategory($category);
        $task->setUser($user);
    
        // Créer le formulaire
        $form = $this->createForm(TaskType::class, $task, [
            'stats' => $stats, // facultatif selon le form type
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_login', []); // ou ta route cible
        }
    
        return $this->render('task/category_new.html.twig', [
            'task' => $task,
            'stats' => $stats,
            'form' => $form,
            'category' => $category,
        ]);
    }

    #[Route('/{id}/new-goal', name: 'app_category_goal_new', methods: ['GET', 'POST'])]
        public function newGoal(int $id, Request $request, Security $security, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
        {
            $user = $security->getUser();

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }
        
            $category = $categoryRepository->find($id);
            $goal = new Goal();
            $goal->setUser($user);
            $goal->setCategory($category);

        
            $form = $this->createForm(GoalType::class, $goal, []);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $category->addGoal($goal);

                $entityManager->persist($goal);
                $entityManager->flush();

                return $this->redirectToRoute('app_login', []);
            }

            return $this->render('goal/category_new.html.twig', [
                'goal' => $goal,
                'form' => $form,
            ]);
        }
}
