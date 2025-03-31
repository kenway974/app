<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Stat;
use App\Entity\Task;
use App\Form\RegistrationFormType;
use App\Repository\StatRepository;
use App\Form\TaskType;
use App\Form\UserStatType;
use App\Security\EmailVerifier;
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

    #[Route('/', name: 'profile')]
        public function profile(User $user): Response
        {
            $tasks = $user->getTasks();
            $stats = $user->getStats();

        return $this->render('registration/profile.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
            'stats' => $stats,
    ]);
    }

    // AJOUT STAT USER
    #[Route('/add-stat', name: 'app_stat_add', methods: ['GET', 'POST'])]
    public function statsUser(Request $request, StatRepository $statRepository, EntityManagerInterface $entityManager, User $user): Response
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


    #[Route('/new-task', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, User $user, StatRepository $statRepository): Response
    {
        if (!$user) {
            // Si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        $stats = $user->getStats();
        $task = new Task();
        $task->setUser($user);
        $form = $this->createForm(TaskType::class, $task, ['stats' => $stats]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedStats =  $form->get('stats')->getData();
            foreach ($selectedStats as $selectedStat) 
            {
                $selectedStat->addTask($task);
            }
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', []);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'stats' => $stats,
            'form' => $form,
        ]);
    }

}