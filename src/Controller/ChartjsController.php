<?php

namespace App\Controller;

use App\Entity\Chartjs;
use App\Form\ChartjsType;
use App\Repository\ChartjsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chartjs')]
class ChartjsController extends AbstractController
{
    #[Route('/', name: 'app_chartjs_index', methods: ['GET'])]
    public function index(ChartjsRepository $chartjsRepository): Response
    {
        return $this->render('chartjs/index.html.twig', [
            'chartjs' => $chartjsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chartjs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChartjsRepository $chartjsRepository): Response
    {
        $chartj = new Chartjs();
        $form = $this->createForm(ChartjsType::class, $chartj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chartjsRepository->save($chartj, true);

            return $this->redirectToRoute('app_chartjs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chartjs/new.html.twig', [
            'chartj' => $chartj,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chartjs_show', methods: ['GET'])]
    public function show(Chartjs $chartj): Response
    {
        return $this->render('chartjs/show.html.twig', [
            'chartj' => $chartj,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chartjs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chartjs $chartj, ChartjsRepository $chartjsRepository): Response
    {
        $form = $this->createForm(ChartjsType::class, $chartj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chartjsRepository->save($chartj, true);

            return $this->redirectToRoute('app_chartjs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chartjs/edit.html.twig', [
            'chartj' => $chartj,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chartjs_delete', methods: ['POST'])]
    public function delete(Request $request, Chartjs $chartj, ChartjsRepository $chartjsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chartj->getId(), $request->request->get('_token'))) {
            $chartjsRepository->remove($chartj, true);
        }

        return $this->redirectToRoute('app_chartjs_index', [], Response::HTTP_SEE_OTHER);
    }
}
