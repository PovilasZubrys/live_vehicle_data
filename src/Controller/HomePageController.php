<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(EntityManagerInterface $em, ChartBuilderInterface $chartBuilder): Response
    {
        $sql = 'SELECT * FROM speed ORDER BY id DESC LIMIT 50';
        $stmt = $em->getConnection()->prepare($sql);
        $speed = $stmt->executeQuery()->fetchAllAssociative();

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $labels = [];
        $speedData = [];

        foreach ($speed as $value) {
            $labels[] = (int) $value['value'];
            $speedData[] = (int) $value['value'];
        }

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Speed',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $speedData,
                    'tension' => 0.4
                ]
            ]
        ]);
        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 300,
                ]
            ],
        ]);
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'chart' => $chart
        ]);
    }

    #[Route('/getSpeed', name: 'app_get_speed')]
    public function getSpeed(EntityManagerInterface $em)
    {
        $sql = 'SELECT value FROM speed ORDER BY id DESC LIMIT 1';
        $stmt = $em->getConnection()->prepare($sql);

        return $this->json((int) $stmt->executeQuery()->fetchOne());
    }
}
