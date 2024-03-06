<?php

namespace App\Model;

use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

class VehicleModel
{
    const AVAILABLE_ENTITIES = [
        'speed' => Speed::class,
        'rpm' => Rpm::class
    ];

    private object $em;

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

    public function getVehicleData(int $id, string $dataType): int|null
    {
        $value = $this->em->getRepository(self::AVAILABLE_ENTITIES[$dataType])->findOneBy(['vehicle' => $id], ['id' => 'DESC']);

        if (is_null($value)) {
            return $value;
        }
        return $value->getValue();
    }
}