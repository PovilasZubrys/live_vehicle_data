<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Vehicle;
use App\Form\DeviceType;
use App\Model\DeviceModel;
use App\Model\VehicleModel;
use Detection\MobileDetect;
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
    public function index(Request $request): Response
    {
        $device = new Device();

        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $device->setUser($this->getUser());
            $this->em->persist($device);
            $this->em->flush();

            return $this->redirectToRoute('app_device');
        }

        $devices = $this->em->getRepository(Device::class)->findAll();

        return $this->render('device/index.html.twig', [
            'controller_name' => 'DeviceController',
            'devices' => $devices,
            'form' => $form,
            'is_mobile' => (new MobileDetect())->isMobile()
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

        $this->addFlash('success', 'Device deleted successfully.');
        return $this->redirectToRoute('app_device');
    }
}
