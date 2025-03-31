<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Stat;
use App\Form\StatType;
use App\Form\UserStatType;
use App\Repository\StatRepository;
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
    #[IsGranted('ROLE_ADMIN')]
    #[Route('\stats-admin', name: 'app_stats_admin', methods: ['GET'])]
    public function statsAdmin(StatRepository $statRepository): Response
    {
        return $this->render('stat/stats_admin.html.twig', [
            'stats' => $statRepository->findAll(),
        ]);
    }

    

    // NEW STAT ADMIN
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_stat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stat = new Stat();
        $form = $this->createForm(StatType::class, $stat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stat);
            $entityManager->flush();

            return $this->redirectToRoute('app_stats_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stat/new.html.twig', [
            'stat' => $stat,
            'form' => $form,
        ]);
    }
    
    // STAT ADMIN
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_stat_show', methods: ['GET'])]
    public function show(Stat $stat): Response
    {
        return $this->render('stat/show.html.twig', [
            'stat' => $stat,
        ]);
    }

    // EDIT STAT ADMIN
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
