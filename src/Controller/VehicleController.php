<?php

namespace App\Controller;

use App\Entity\Speed;
use App\Entity\Vehicle;
use App\Model\VehicleModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class VehicleController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/vehicles', name: 'app_vehicles')]
    public function index(Request $request, VehicleModel $vehicle): Response
    {
        if ($request->isMethod('POST')) {
            if ($vehicle->processNewVehicle($request->request->all())) {
                $this->addFlash('success', 'Vehicle added successfully!');
            } else {
                $this->addFlash('warning', 'We were not able to add the vehicle :(');
            }
            return $this->redirectToRoute('app_vehicles');
        }

        $vehicles = $this->em->getRepository(Vehicle::class)->findAll();

        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
            'vehicles' => $vehicles
        ]);
    }

    #[Route('/track_vehicle/{id}', name: 'app_track_vehicles')]
    public function trackVehicle(ChartBuilderInterface $chartBuilder, $id): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $labels = [];
        $speedData = [];

        $speedResults = $this->em->getRepository(Speed::class)->findBy(['vehicle' => $id], ['id' => 'DESC'], 50);

        foreach ($speedResults as $value) {
            $labels[] = (int) $value->getValue();
            $speedData[] = (int) $value->getValue();
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

        return $this->render('vehicle/track_vehicles.html.twig', [
            'controller_name' => 'VehicleController',
            'chart' => $chart,
            'vehicle_id' => $id
        ]);
    }

    #[Route('/get_vehicle_data/{id}/{dataType}', name: 'app_get_vehicle_data')]
    public function getData(VehicleModel $vehicleModel, $id, $dataType): Response
    {
        return $this->json((int) $vehicleModel->getVehicleData($id,$dataType));
    }
}
