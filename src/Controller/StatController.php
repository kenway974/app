<?php

namespace App\Controller;

use App\Entity\Stat;
use App\Form\StatType;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stat')]
final class StatController extends AbstractController
{
    #[Route(name: 'app_stat_index', methods: ['GET'])]
    public function index(StatRepository $statRepository): Response
    {
        return $this->render('stat/index.html.twig', [
            'stats' => $statRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stat = new Stat();
        $form = $this->createForm(StatType::class, $stat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stat);
            $entityManager->flush();

            return $this->redirectToRoute('app_stat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stat/new.html.twig', [
            'stat' => $stat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stat_show', methods: ['GET'])]
    public function show(Stat $stat): Response
    {
        return $this->render('stat/show.html.twig', [
            'stat' => $stat,
        ]);
    }

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

    #[Route('/{id}', name: 'app_stat_delete', methods: ['POST'])]
    public function delete(Request $request, Stat $stat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stat_index', [], Response::HTTP_SEE_OTHER);
    }
}
