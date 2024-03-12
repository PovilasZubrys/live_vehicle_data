<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $vehicle->setUser($this->getUser());
            $this->em->persist($vehicle);
            $this->em->flush();

            return $this->redirectToRoute('app_vehicles');
        }

        $vehicles = $this->em->getRepository(Vehicle::class)->findAll();
        $devices = $this->em->getRepository(Device::class)->findBy(['vehicle' => null]);

        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles,
            'form' => $form,
            'devices' => $devices
        ]);
    }

    #[Route('/vehicles/track/{id}', name: 'app_track_vehicles')]
    public function track(ChartBuilderInterface $chartBuilder, $id): Response
    {
        if (empty($this->em->getRepository(Vehicle::class)->findOneBy(['id' => $id, 'user' => $this->getUser()]))) {
            $this->addFlash('warning', 'Vehicle not found.');
            return $this->redirectToRoute('app_vehicles');
        }

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
            'mercure_url' => '/vehicle_data/' . $id
        ]);
    }

    #[Route('/vehicles/delete/{id}', name: 'app_delete_vehicles')]
    public function delete($id): Response
    {
        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => $id]);
        $vehicleOwner = $vehicle->getUser();

        if ($this->getUser()->getId() != $vehicleOwner->getId()) {
            $this->addFlash('danger', 'Vehicle not found.');
            return $this->redirectToRoute('app_vehicles');
        }
        $device = $this->em->getRepository(Device::class)->findOneBy(['vehicle' => $vehicle]);
        $device->setVehicle(null);

        $this->em->persist($device);
        $this->em->remove($vehicle);
        $this->em->flush();

        $this->addFlash('success', 'Vehicle deleted successfully.');
        return $this->redirectToRoute('app_vehicles');
    }

    #[Route('/vehicles/assign/{id}/{deviceId}', name: 'app_assign_vehicles')]
    public function assign($id, $deviceId): Response
    {
        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => $id]);
        $device = $this->em->getRepository(Device::class)->findOneBy(['id' => $deviceId]);
        $device->setVehicle($vehicle);

        $this->em->persist($device);
        $this->em->flush();
        return $this->redirectToRoute('app_vehicles');
    }
}
