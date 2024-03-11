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

        if (empty($vehicles)) {
            $this->addFlash('warning', "There are no available vehicles. You won't be able to assign vehicle to the device. Please add vehicle.");
            $form = $this->createForm(DeviceType::class, $device);
        } else {
            $form = $this->createForm(DeviceType::class, $device)
                ->add('vehicle', ChoiceType::class, [
                        'choices' => $vehicles,
                        'attr' => ['class' => 'form-select'],
                        'mapped' => false
                    ]
                );
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $device->setUser($this->getUser());
            $device->setVehicle($form->get('vehicle')->getData());

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

    #[Route('/device/delete/{id}', name: 'app_delete_device')]
    public function delete($id): Response
    {
        $device = $this->em->getRepository(Device::class)->findOneBy(['id' => $id]);
        $deviceOwner = $device->getUser();

        if ($this->getUser()->getId() != $deviceOwner->getId()) {
            $this->addFlash('danger', 'Device not found.');
            return $this->redirectToRoute('app_device');
        }

        $this->em->remove($device);
        $this->em->flush();

        $this->addFlash('success', 'Vehicle deleted successfully.');
        return $this->redirectToRoute('app_vehicles');
    }
}
