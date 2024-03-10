<?php

namespace App\Controller;

use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
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

        $speed = [];
        foreach ($speedResults as $value) {
            $speed[] = (int) $value->getValue();
        }

        $speedChart->setData([
            'labels' => $speed,
            'datasets' => [
                [
                    'label' => 'Speed',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $speed,
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

        $rpm = [];
        foreach ($rpmResults as $value) {
            $rpm[] = (int) $value->getValue();
        }

        $rpmChart->setData([
            'labels' => $rpm,
            'datasets' => [
                [
                    'label' => 'Rpm',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $rpm,
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
            'current_speed' => end($speed),
            'current_rpm' => end($rpm),
            'vehicle_id' => $id
        ]);
    }

    #[Route('/track_vehicle/publish/{id}', name: 'app_track_vehicle_publish')]
    public function publish(EntityManagerInterface $em, HubInterface $hub, $id): Response
    {
        $speed = $em->getRepository(Speed::class)->findOneBy(['vehicle' => $id], ['id' => 'DESC']);
        $rpm = $em->getRepository(Rpm::class)->findOneBy(['vehicle' => $id], ['id' => 'DESC']);
        $update = new Update(
            '/vehicle_data',
            json_encode(
                [
                    'speed' => $speed->getValue(),
                    'rpm' => $rpm->getValue()
                ])
        );

        $hub->publish($update);

        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'VehicleController',

        ]);
    }
}
