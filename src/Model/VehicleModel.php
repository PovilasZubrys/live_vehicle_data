<?php

namespace App\Model;

use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Model\Chart;

class VehicleModel
{
    private object $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getChart($dataEntity, $vehicleId, $label): object
    {
        $chartBuilder = new ChartBuilder();
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $data = $this->em->getRepository($dataEntity)->findBy(['vehicle' => $vehicleId], ['id' => 'DESC'], 50);

        $results = [];
        foreach ($data as $result) {
            $results[] = (int) $result->getValue();
        }

        $chart->setData([
            'labels' => $results,
            'datasets' => [
                [
                    'label' => $label,
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $results,
                    'tension' => 0.4
                ]
            ]
        ]);

        return $chart;
    }
}