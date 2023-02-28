<?php

namespace App\Controller;

use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\DailyResultRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChartjsController extends AbstractController
{
    #[Route('/chartjs', name: 'app_chartjs')]
    public function index(DailyResultRepository $dailyResultRepo,ChartBuilderInterface $chartBuilder): Response
    {
        $dailyResults = $dailyResultRepo->findAll();
        $labels=[];
        $data=[];
        foreach ($dailyResults as $dailyResult) {
           $labels[]=$dailyResult->getDate()->format('d/m/Y');
           $data[]=$dailyResult->getValue();
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 10,
                ],
            ],
        ]);

        return $this->render('chartjs/index.html.twig', [
            'chart' => $chart,
        ]);
    }
}
