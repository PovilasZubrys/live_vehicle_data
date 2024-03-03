<?php

namespace App\Model;

use App\Entity\Device;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeviceModel
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function processNewDevice(array $data): bool
    {
        $device = new Device();

        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => (int) $data['vehicle']]);

        $device
            ->setName($data['name'])
            ->setAuthenticationToken($data['authentication_token'])
            ->setVehicle($vehicle);

        try {
            $this->em->persist($device);
            $this->em->flush();
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }

}