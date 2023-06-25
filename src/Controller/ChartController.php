<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\ux\Chartjs\Model\Chart;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;


 #[Route('/chart')]
 class ChartController extends AbstractController
  {
    #[Route('/', name: 'app_chart_index', methods: ['GET'])]
     public function index (ChartBuilderInterface $chartBuilder): Response
     {
         $chart = $chartBuilder->createChart( type: Chart::TYPE_LINE);
        $chart->setData([
             'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
             'datasets' => [
                 [
                     'label' => 'My First dataset',
                     'backgroundColor' => 'rgb(255, 99, 132)',
                     'borderColor' => 'rgb(255, 99, 132)',
                     'data' => [0, 10, 5, 2, 20, 30, 45],
                 ],
                ],
         ]);
         
         return $this->render('chart/index.html.twig', [
             'chart' => $chart,
         ]);
    }
    
}