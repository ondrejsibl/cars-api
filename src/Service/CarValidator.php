<?php
namespace App\Service;

use App\Exception\InvalidAttributeException;
use App\Exception\MissingAttributeException;
use App\Repository\ColorRepository;
use DateTime;

class CarValidator
{
    const ALLOWED_AGE = 4;

    /**
     * @var ColorRepository
     */
    private ColorRepository $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    /**
     * @param array $carData
     * @throws MissingAttributeException|InvalidAttributeException
     */
    public function checkCarData(array $carData): void
    {
        if (!array_key_exists('make', $carData) || $carData['make'] === '' || $carData['make'] === null) {
            throw new MissingAttributeException('Missing make attribute');
        }
        if (!array_key_exists('model', $carData) || $carData['model'] === '' || $carData['model'] === null) {
            throw new MissingAttributeException('Missing model attribute');
        }
        $this->checkColor($carData);
        $this->checkBuildDate($carData);
    }

    /**
     * @param array $carData
     * @throws MissingAttributeException|InvalidAttributeException
     */
    private function checkColor(array $carData): void
    {
        if (!array_key_exists('color', $carData) || $carData['color'] === '' || $carData['color'] === null) {
            throw new MissingAttributeException('Missing color attribute');
        }

        if ($this->colorRepository->findOneBy(['name' => $carData['color']]) === null) {
            throw new InvalidAttributeException('Invalid color');
        }
    }

    /**
     * @param array $carData
     * @throws MissingAttributeException|InvalidAttributeException
     */
    private function checkBuildDate(array $carData): void
    {
        if (!array_key_exists('buildDate', $carData) || $carData['buildDate'] === null) {
            throw new MissingAttributeException('Missing buildDate attribute');
        }

        try {
            $buildDate = new DateTime($carData['buildDate']);
        } catch (\Throwable $e) {
            throw new InvalidAttributeException('Invalid buildDate');
        }

        $currentDate = new DateTime();

        if ($buildDate->diff($currentDate)->y > self::ALLOWED_AGE) {
            throw new InvalidAttributeException('Car can\'t be older than ' . self::ALLOWED_AGE . 'years');
        }
    }
}
