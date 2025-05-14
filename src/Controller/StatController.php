<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Stat;
use App\Form\StatType;
use App\Form\UserStatType;
use App\Repository\StatRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Event\UserRegistrationEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/stat')]
final class StatController extends AbstractController
{
   // LISTE STAT ADMIN
    #[Route('\stats-index', name: 'app_stat_index', methods: ['GET'])]
    public function statsAdmin(StatRepository $statRepository): Response
    {
        return $this->render('stat/stats_admin.html.twig', [
            'stats' => $statRepository->findAll(),
        ]);
    }

    

    // NEW STAT USER
    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_stat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Security $security, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $user = $security->getUser();

        $categories = $categoryRepository->findAll();

        $stat = new Stat();
        $stat->setUser($user);
        $form = $this->createForm(StatType::class, $stat, ['categories'=>$categories]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $stat->setScore(0);
            $entityManager->persist($stat);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stat/new.html.twig', [
            'stat' => $stat,
            'form' => $form,
        ]);
    }
    
    // STAT USER
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', name: 'app_stat_show', methods: ['GET'])]
    public function show(Stat $stat): Response
    {
        return $this->render('stat/show.html.twig', [
            'stat' => $stat,
        ]);
    }

    // EDIT STAT USER
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_stat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stat $stat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatType::class, $stat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stat/edit.html.twig', [
            'stat' => $stat,
            'form' => $form,
        ]);
    }

    // DELETE STAT ADMIN
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'app_stat_delete', methods: ['POST'])]
    public function delete(Request $request, Stat $stat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stat_index', [], Response::HTTP_SEE_OTHER);
    }

}
