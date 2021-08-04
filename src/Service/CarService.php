<?php
namespace App\Service;

use App\Entity\Car;
use App\Entity\Color;
use App\Exception\InvalidAttributeException;
use App\Repository\CarRepository;
use App\Repository\ColorRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarService
{
    private EntityManagerInterface $em;
    private CarRepository $carRepository;
    private CarValidator $carValidator;
    private ColorRepository $colorRepository;

    public function __construct(EntityManagerInterface $em, CarRepository $carRepository, ColorRepository $colorRepository, CarValidator $carValidator)
    {
        $this->em = $em;
        $this->carRepository = $carRepository;
        $this->colorRepository = $colorRepository;
        $this->carValidator = $carValidator;
    }

    /**
     * @param Request $request
     * @return Car
     * @throws Exception|InvalidAttributeException
     */
    public function createCarFromRequest(Request $request): Car
    {
        $content = $request->getContent();
        if ($content === '') {
            throw new InvalidAttributeException('Missing car data');
        }
        $carData = json_decode($content, true);
        $this->carValidator->checkCarData($carData);

        $car = $this->createCar($carData);

        return $car;
    }

    /**
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function getCar(int $id): array
    {
        $car = $this->carRepository->getOneAsArray($id);
        if ($car === null) {
            throw new NotFoundHttpException('Car not found');
        }

        return $car;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->carRepository->getAllAsArray();
    }

    /**
     * @param int $id
     * @throws NotFoundHttpException
     */
    public function removeCar(int $id): void
    {
        $car = $this->carRepository->find($id);
        if ($car === null) {
            throw new NotFoundHttpException('Car not found');
        }
        $this->carRepository->remove($car);
    }

    /**
     * @param string $colorName
     * @return Color
     * @throws InvalidAttributeException
     */
    private function getColor(string $colorName): Color
    {
        $color = $this->colorRepository->findOneBy(['name' => $colorName]);
        if ($color === null) {
            throw new InvalidAttributeException('Invalid color');
        }

        return $color;
    }

    /**
     * @param array $carData
     * @return Car
     * @throws Exception
     */
    private function createCar(array $carData): Car
    {
        $car = new Car();
        $car->setMake($carData['make']);
        $car->setModel($carData['model']);
        $car->setBuildDate(new DateTime($carData['buildDate']));
        $car->setColor($this->getColor($carData['color']));

        $this->carRepository->store($car);

        return $car;
    }
}
