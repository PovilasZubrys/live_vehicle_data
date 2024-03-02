<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Model\VehicleModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VehicleController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/vehicle', name: 'app_vehicle')]
    public function index(Request $request, VehicleModel $vehicle): Response
    {
        if ($request->isMethod('POST')) {
            if ($vehicle->processNewVehicle($request->request->all())) {
                $this->addFlash('notice', 'Vehicle added successfully');
            }
            return $this->redirectToRoute('app_vehicle');
        }

        $vehicles = $this->em->getRepository(Vehicle::class)->findAll();


        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
            'vehicles' => $vehicles
        ]);
    }
}
