<?php

namespace App\Controller;

use App\Entity\DailyResult;
use App\Form\DailyResultType;
use App\Repository\DailyResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/daily/result')]
class DailyResultController extends AbstractController
{
    #[Route('/', name: 'app_daily_result_index', methods: ['GET'])]
    public function index(DailyResultRepository $dailyResultRepository): Response
    {
        return $this->render('daily_result/index.html.twig', [
            'daily_results' => $dailyResultRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_daily_result_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DailyResultRepository $dailyResultRepository): Response
    {
        $dailyResult = new DailyResult();
        $form = $this->createForm(DailyResultType::class, $dailyResult);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dailyResultRepository->save($dailyResult, true);

            return $this->redirectToRoute('app_daily_result_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('daily_result/new.html.twig', [
            'daily_result' => $dailyResult,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_daily_result_show', methods: ['GET'])]
    public function show(DailyResult $dailyResult): Response
    {
        return $this->render('daily_result/show.html.twig', [
            'daily_result' => $dailyResult,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_daily_result_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DailyResult $dailyResult, DailyResultRepository $dailyResultRepository): Response
    {
        $form = $this->createForm(DailyResultType::class, $dailyResult);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dailyResultRepository->save($dailyResult, true);

            return $this->redirectToRoute('app_daily_result_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('daily_result/edit.html.twig', [
            'daily_result' => $dailyResult,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_daily_result_delete', methods: ['POST'])]
    public function delete(Request $request, DailyResult $dailyResult, DailyResultRepository $dailyResultRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dailyResult->getId(), $request->request->get('_token'))) {
            $dailyResultRepository->remove($dailyResult, true);
        }

        return $this->redirectToRoute('app_daily_result_index', [], Response::HTTP_SEE_OTHER);
    }
}
