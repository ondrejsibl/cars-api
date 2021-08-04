<?php

namespace App\Controller;

use App\Service\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CarsController extends AbstractController
{
    private CarService $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * @Route("/cars", name="get-cars", methods={"GET"})
     */
    public function getCars(): JsonResponse
    {
        $cars = $this->carService->getAll();

        return $this->returnSuccess($cars);
    }

    /**
     * @Route("/cars", name="add-car", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addCar(Request $request): JsonResponse
    {
        try {
            $car = $this->carService->createCarFromRequest($request);
        }
        catch(HttpExceptionInterface $e) {
            return $this->returnError($e);
        }

        return $this->returnSuccess(['id' => $car->getId()]);
    }

    /**
     * @Route("/cars/{id}", name="get-car", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getCar(int $id): JsonResponse
    {
        try {
            $car = $this->carService->getCar($id);
        }
        catch(HttpExceptionInterface $e) {
            return $this->returnError($e);
        }

        return $this->returnSuccess($car);
    }

    /**
     * @Route("/cars/{id}", name="delete-car", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function deleteCar(int $id): JsonResponse
    {
        try {
            $this->carService->removeCar($id);
        }
        catch(HttpExceptionInterface $e) {
            return $this->returnError($e);
        }

        return $this->returnSuccess([]);
    }

    private function returnSuccess(array $data): JsonResponse
    {
        return $this->json(['data' => $data], Response::HTTP_OK);
    }

    private function returnError(HttpExceptionInterface $e): JsonResponse
    {
        return $this->json(['error' => $e->getMessage()], $e->getStatusCode());
    }
}
