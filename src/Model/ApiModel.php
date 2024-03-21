<?php

namespace App\Model;

use App\Entity\CoolantTemp;
use App\Entity\EngineLoad;
use App\Entity\Gps;
use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

class ApiModel
{
    private $em;

    private const DATATYPE_ENTITIES = [
        'speed' => Speed::class,
        'rpm' => Rpm::class,
        'engine_load' => EngineLoad::class,
        'coolant_temp' => CoolantTemp::class,
        'gps' => Gps::class
    ];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function processData(array $data): array
    {
        $invalidFields = [];

        foreach ($data as $dataPoint) {
            $result = $this->validateFields($dataPoint);
            $dataPoint = $this->convertDate($dataPoint);

            if (isset($result['status']) && $result['status'] == 200) {
                $dataEntity = self::DATATYPE_ENTITIES[$dataPoint->data_type];
                $dataEntity = new $dataEntity;

                if ($dataPoint->data_type == 'gps') {
                    $dataEntity->setLatitude(json_decode($dataPoint->value)->latitude);
                    $dataEntity->setLongitude(json_decode($dataPoint->value)->longitude);
                } else {
                    $dataEntity->setValue($dataPoint->value);
                }
                $dataEntity->setDate($dataPoint->date);
                $dataEntity->setVehicle($result['vehicle']);

                $this->em->persist($dataEntity);
                $this->em->flush();
            } else {
                $invalidFields[] = $result;
            }
        }

        if (!empty($invalidFields)) {
            $invalidFields['status'] = 401;

            return $invalidFields;
        }

        return ['status' => 200];
    }

    private function convertDate(object $dataPoint): object
    {
        $dataPoint->date = new \DateTime($dataPoint->date);

        return $dataPoint;
    }

    private function validateFields(object $dataPoint)
    {
        $validationResult = [];

        if (!isset($dataPoint->vehicle_id)) {
            $validationResult[] = [
                'message' => 'Missing vehicle_id field'
            ];
        }

        if (!isset($dataPoint->data_type)) {
            $validationResult[] = [
                'message' => 'Missing data_type field'
            ];
        }

        if (!isset($dataPoint->value)) {
            $validationResult[] = [
                'message' => 'Missing data_type field'
            ];
        }

        if (!isset($dataPoint->date)) {
            $validationResult[] = [
                'message' => 'Missing data_type field'
            ];
        }

        if (isset($dataPoint->data_type) && !isset(self::DATATYPE_ENTITIES[$dataPoint->data_type])) {
            $validationResult[] = [
                'message' => "Can't accept this data type: " . $dataPoint->data_type
            ];
        }

        if (isset($dataPoint->vehicle_id)) {
            $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => $dataPoint->vehicle_id]);
        }

        if (empty($vehicle) && isset($dataPoint->vehicle_id)) {
            $validationResult[] = [
                'message' => 'Vehicle by id ' . $dataPoint->vehicle_id . ' not found'
            ];
        }

        if (empty($validationResult)) {
            return [
                'status' => 200,
                'vehicle' => $vehicle
            ];
        } else {
            return $validationResult;
        }
    }
}