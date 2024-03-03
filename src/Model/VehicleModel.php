<?php

namespace App\Model;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

class VehicleModel
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function processNewVehicle(array $data): bool
    {
        $vehicle = new Vehicle();

        $vehicle
            ->setType($data['type'])
            ->setMake($data['make'])
            ->setModel($data['model'])
            ->setYear($data['year']);

        try {
            $this->em->persist($vehicle);
            $this->em->flush();
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }

    public function getVehicleData(int $id, string $dataType): string|int
    {
        $sql = "SELECT value FROM $dataType WHERE vehicle_id = $id ORDER BY id DESC";

        $response = $this->em->getConnection()->prepare($sql)->executeQuery()->fetchOne();

        return $response;
    }
}