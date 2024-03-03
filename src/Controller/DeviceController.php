<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Vehicle;
use App\Model\DeviceModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeviceController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/device', name: 'app_device')]
    public function index(Request $request, DeviceModel $device): Response
    {
        if ($request->isMethod('POST')) {
            if ($device->processNewDevice($request->request->all())) {
                $this->addFlash('success', 'Device added successfully!');
            } else {
                $this->addFlash('warning', 'There was an error on inserting new device :(');
            }
            return $this->redirectToRoute('app_device');
        }

        $vehicles = $this->em->getRepository(Vehicle::class)->findAll();
        $devices = $this->em->getRepository(Device::class)->findAll();

        return $this->render('device/index.html.twig', [
            'controller_name' => 'DeviceController',
            'vehicles' => $vehicles,
            'devices' => $devices
        ]);
    }
}
