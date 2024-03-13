<?php

namespace App\Controller;

use App\Entity\CoolantTemp;
use App\Entity\Device;
use App\Entity\EngineLoad;
use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use App\Form\VehicleType;
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
    public function track(VehicleModel $vehicleModel, $id): Response
    {
        if (empty($this->em->getRepository(Vehicle::class)->findOneBy(['id' => $id, 'user' => $this->getUser()]))) {
            $this->addFlash('warning', 'Vehicle not found.');
            return $this->redirectToRoute('app_vehicles');
        }

        $speedChart = $vehicleModel->getChart(Speed::class, $id, 'Speed');
        $rpmChart = $vehicleModel->getChart(Rpm::class, $id, 'Rpm');
        $engineLoadChart = $vehicleModel->getChart(EngineLoad::class, $id, 'Engine load');
        $coolantTempChart = $vehicleModel->getChart(CoolantTemp::class, $id, 'Coolant temp');

        return $this->render('vehicle/track_vehicles.html.twig', [
            'controller_name' => 'VehicleController',
            'speedChart' => $speedChart,
            'rpmChart' => $rpmChart,
            'engineLoadChart' => $engineLoadChart,
            'coolantTempChart' => $coolantTempChart,
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

    #[Route('/vehicles/unassign_device/{id}', name: 'app_unassign_devices')]
    public function unassign($id): Response
    {
        $device = $this->em->getRepository(Device::class)->findOneBy(['id' => $id]);
        $device->setVehicle(null);

        $this->em->persist($device);
        $this->em->flush();
        return $this->redirectToRoute('app_vehicles');
    }
}
