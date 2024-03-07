<?php

namespace App\Controller;

use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Model\VehicleModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class VehicleController extends AbstractController
{
    private object $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/vehicles', name: 'app_vehicles')]
    public function index(Request $request): Response
    {
        $vehicle = new Vehicle();

        $form = $this->createForm(VehicleType::class, $vehicle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($vehicle);
            $this->em->flush();

            return $this->redirectToRoute('app_vehicles');
        }

        $vehicles = $this->em->getRepository(Vehicle::class)->findAll();

        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
            'vehicles' => $vehicles,
            'form' => $form
        ]);
    }

    #[Route('/track_vehicle/{id}', name: 'app_track_vehicles')]
    public function trackVehicle(ChartBuilderInterface $chartBuilder, $id): Response
    {
        $speedChart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $speedResults = $this->em->getRepository(Speed::class)->findBy(['vehicle' => $id], ['id' => 'DESC'], 50);
        $rpmResults = $this->em->getRepository(Rpm::class)->findBy(['vehicle' => $id], ['id' => 'DESC'], 50);

        $speedLabels = [];
        $speedData = [];

        foreach ($speedResults as $value) {
            $speedLabels[] = (int) $value->getValue();
            $speedData[] = (int) $value->getValue();
        }

        $speedChart->setData([
            'labels' => $speedLabels,
            'datasets' => [
                [
                    'label' => 'Speed',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $speedData,
                    'tension' => 0.4
                ]
            ]
        ])->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 300,
                ]
            ]
        ]);

        $rpmChart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $rpmLabels = [];
        $rpmData = [];

        foreach ($rpmResults as $value) {
            $rpmLabels[] = (int) $value->getValue();
            $rpmData[] = (int) $value->getValue();
        }

        $rpmChart->setData([
            'labels' => $rpmLabels,
            'datasets' => [
                [
                    'label' => 'Rpm',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $rpmData,
                    'tension' => 0.4
                ]
            ]
        ]);
        $rpmChart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 12500,
                ]
            ],
        ]);

        return $this->render('vehicle/track_vehicles.html.twig', [
            'controller_name' => 'VehicleController',
            'speedChart' => $speedChart,
            'rpmChart' => $rpmChart,
            'vehicle_id' => $id
        ]);
    }

    #[Route('/get_vehicle_data/{dataType}/{id}', name: 'app_get_vehicle_data')]
    public function getData(VehicleModel $vehicleModel, $dataType, $id): Response
    {
        return $this->json($vehicleModel->getVehicleData($id,$dataType));
    }
}
