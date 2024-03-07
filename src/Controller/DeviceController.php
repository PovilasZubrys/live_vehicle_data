<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Vehicle;
use App\Form\DeviceType;
use App\Model\DeviceModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeviceController extends AbstractController
{
    private object $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/device', name: 'app_device')]
    public function index(Request $request, DeviceModel $deviceModel): Response
    {
        $vehicleChoices = $deviceModel->getVehicleChoices();
        $device = new Device();

        if (empty($vehicleChoices)) {
            $this->addFlash('warning', "There are not available vehicles. You won't be able to assign vehicle to the device. Please add vehicle.");
            $form = $this->createForm(DeviceType::class, $device);
        } else {
            $form = $this->createForm(DeviceType::class, $device)
                ->add('vehicle', ChoiceType::class, [
                        'choices' => $vehicleChoices,
                        'attr' => ['class' => 'form-select'],
                        'mapped' => false
                    ]
                );
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('vehicle')->getData()) {
                $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => $form->get('vehicle')->getData()]);
                $vehicle->setDevice($device);
                $this->em->persist($vehicle);
            }
            $this->em->persist($device);
            $this->em->flush();

            return $this->redirectToRoute('app_device');
        }

        $devices = $this->em->getRepository(Device::class)->findAll();
        return $this->render('device/index.html.twig', [
            'controller_name' => 'DeviceController',
            'devices' => $devices,
            'form' => $form
        ]);
    }
}
