<?php

namespace App\Controller;

use App\Entity\Trophy;
use App\Form\TrophyType;
use App\Repository\TrophyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/trophy')]
final class TrophyController extends AbstractController
{
    #[Route(name: 'app_trophy_index', methods: ['GET'])]
    public function index(TrophyRepository $trophyRepository): Response
    {
        return $this->render('trophy/index.html.twig', [
            'trophies' => $trophyRepository->findAll(),
        ]);
    }

    #[Route('/new-trophy', name: 'app_trophy_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        $trophy = new Trophy();
        $trophy->addUser($user);

        $form = $this->createForm(TrophyType::class, $trophy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trophy);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trophy/new.html.twig', [
            'trophy' => $trophy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trophy_show', methods: ['GET'])]
    public function show(Trophy $trophy): Response
    {
        return $this->render('trophy/show.html.twig', [
            'trophy' => $trophy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trophy_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trophy $trophy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrophyType::class, $trophy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trophy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trophy/edit.html.twig', [
            'trophy' => $trophy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trophy_delete', methods: ['POST'])]
    public function delete(Request $request, Trophy $trophy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trophy->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trophy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trophy_index', [], Response::HTTP_SEE_OTHER);
    }
}
