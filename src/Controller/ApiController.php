<?php

namespace App\Controller;

use App\Model\ApiModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/send/vehicle_data', name: 'app_send_vehicle_data', methods: 'POST')]
    public function sendVehicleData(Request $request, ApiModel $apiModel): Response
    {
        $result = $apiModel->processData(json_decode($request->getContent()));

        $response = new Response();

        $response->setStatusCode($result['status']);
        $response->setContent(json_encode($result));

        return $response;
    }

    #[Route('/api/get/test', name: 'app_get_test', methods: 'GET')]
    public function getTest(): Response
    {

        return $this->json('test');
    }
}
