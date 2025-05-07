<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Stat;
use App\Entity\Category;
use App\Entity\Goal;
use App\Entity\Task;
use App\Form\RegistrationFormType;
use App\Repository\StatRepository;
use App\Repository\GoalRepository;
use App\Repository\CategoryRepository;
use App\Form\GoalType;
use App\Form\TaskType;
use App\Form\UserStatType;
use App\Repository\TaskRepository;
use App\Security\EmailVerifier;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

#[Route('/profile/{id}')]
class ProfileController extends AbstractController

{
    public function statProgress(User $user):  void {
        
        $tasks = $user->getTasks();
        
        foreach($tasks as $task) {
            // PROPRIETES DE CHAQUE TACHE
            $dateButoir = $task->getDateButoir();
            $importance = $task->getImportance();
            $stats = $task->getStats();
            $checked = $task->isChecked();
            // CHANGE STAT SELON DATE ET CHECKED
            if ($dateButoir < new DateTime() && $checked == true) {
                foreach($stats as $stat) {
                    $stat->setScore($stat->getScore() + $importance);
                }
            }
            elseif ($dateButoir < new DateTime() && $checked == false) {
                foreach($stats as $stat) {
                    if($stat->getScore() >= $importance)
                    $stat->setScore($stat->getScore() - $importance);
                }
            }
        }
    }

        // PAGE ADMIN
    #[Route('/admin', name: 'admin')]
        public function admin(User $user, StatRepository $statRepository, CategoryRepository $categoryRepository): Response
        {
            $stats = $statRepository->findAll();
            $categories = $categoryRepository->findAll();
            
            $this->statProgress($user);

            return $this->render('profile/admin.html.twig', [
                'user' => $user,
                'stats' => $stats,
                'categories' => $categories,
            ]);
        }

        // AFFICHAGE DU PROFIL + STATPROGRESS
    #[Route('/', name: 'profile')]
        public function profile(User $user, GoalRepository $goalRepository, CategoryRepository $categoryRepository): Response
        {
            $tasks = $user->getTasks();
            $stats = $user->getStats();
            $goals = $goalRepository->findbyCategory();
            $categories = $categoryRepository->findAll();
           

            $this->statProgress($user);

            return $this->render('profile/profile.html.twig', [
                'user' => $user,
                'tasks' => $tasks,
                'stats' => $stats,
                'goals' => $goals,
                'categories' => $categories,
            ]);
        }

            // CREATION GOAL
    #[Route('/new-goal', name: 'app_goal_new', methods: ['GET', 'POST'])]
        public function newGoal(User $user,Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
        {
            if (!$user) {
                return $this->redirectToRoute('app_login');
            }
        
            $categories = $categoryRepository->findAll();

            $goal = new Goal();
            $goal->setUser($user);

        
            $form = $this->createForm(GoalType::class, $goal, ['categories'=>$categories]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            
                $category = $form->get('category')->getData();
                $category->addGoal($goal);

                $entityManager->persist($goal);
                $entityManager->flush();

                return $this->redirectToRoute('app_login', []);
            }

            return $this->render('goal/new.html.twig', [
                'goal' => $goal,
                'categories' => $categories,
                'form' => $form,
            ]);
        }

        
            // AJOUT STAT USER
    #[Route('/add-stat', name: 'app_stat_add', methods: ['GET', 'POST'])]
    public function addStatUser(Request $request, StatRepository $statRepository, EntityManagerInterface $entityManager, User $user): Response
    {   
        $stats = $statRepository->findAll();
        
        $form = $this->createForm(UserStatType::class, null, ['stats' => $stats]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedStats = $form->get('stats')->getData();
            foreach ($selectedStats as $stat) 
            {
                $stat->addUser($user);
                $entityManager->persist($stat);
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stat/stats_user.html.twig', [
                'form' => $form->createView(),
                'stats' => $stats
            ]);
    }

        // NOUVELLE TACHE + AJOUT AU USER
    #[Route('/new-task', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function newTask(Request $request, EntityManagerInterface $entityManager, User $user, CategoryRepository $categoryRepository): Response
    {
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $categories = $categoryRepository->findAll();
        $stats = $user->getStats();
        
        $task = new Task();
        $task->setUser($user);
        
        $form = $this->createForm(TaskType::class, $task, [
            'stats' => $stats,
            'categories' => $categories,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*$selectedStats =  $form->get('stats')->getData();
            foreach ($selectedStats as $selectedStat) 
            {
                $selectedStat->addTask($task);
            }

            $selectedCategories =  $form->get('categories')->getData();
            foreach ($selectedCategories as $selectedCategory) 
            {
                $selectedCategory->addTask($task);
            }*/
            
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', []);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'stats' => $stats,
            'categories' => $categories,
            'form' => $form,
        ]);
    }

    
}