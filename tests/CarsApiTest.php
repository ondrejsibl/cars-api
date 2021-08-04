<?php

namespace App\Tests;

use DateTime;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CarsApiTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testAddCar(): string
    {
        $buildDate = new DateTime();
        $buildDate->modify("-1 year");
        $carData = ['model' => 'Focus', 'make' => 'Ford', 'color' => 'white', 'buildDate' => $buildDate->format('Y-m-d')];

        $this->client->request('POST', '/cars', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($carData));
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($responseData['data']['id']);
        $this->assertJson($response->getContent());

        return $responseData['data']['id'];
    }

    /**
     * @depends testAddCar
     * @param string $carId
     */
    public function testGetCar(string $carId): void
    {
        $this->client->request('GET', '/cars/' . $carId);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals($carId, $responseData['data']['id']);
        $this->assertJson($response->getContent());
    }

    /**
     * @depends testAddCar
     */
    public function testGetCars(): void
    {
        $this->client->request('GET', '/cars');
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $responseData['data'][0]['id']);
        $this->assertJson($response->getContent());
    }

    /**
     * @depends testAddCar
     * @param string $carId
     * @return string
     */
    public function testDeleteCar(string $carId): string
    {
        $this->client->request('DELETE', '/cars/' . $carId);

        $this->assertResponseIsSuccessful();

        return $carId;
    }

    /**
     * @depends testDeleteCar
     * @param string $carId
     */
    public function testCarNotFound(string $carId): void
    {
        $this->client->request('GET', '/cars/' . $carId);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAddCarWrongColor(): void
    {
        $buildDate = new DateTime();
        $buildDate->modify("-1 year");
        $carData = ['model' => 'Focus', 'make' => 'Ford', 'color' => 'modrá', 'buildDate' => $buildDate->format('Y-m-d')];

        $this->client->request('POST', '/cars', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($carData));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAddCarMissingModel(): void
    {
        $buildDate = new DateTime();
        $buildDate->modify("-1 year");
        $carData = ['make' => 'Ford', 'color' => 'white', 'buildDate' => $buildDate->format('Y-m-d')];

        $this->client->request('POST', '/cars', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($carData));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAddCarMissingMake(): void
    {
        $buildDate = new DateTime();
        $buildDate->modify("-1 year");
        $carData = ['model' => 'Focus', 'color' => 'black', 'buildDate' => $buildDate->format('Y-m-d')];

        $this->client->request('POST', '/cars', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($carData));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAddCarWrongBuildDate(): void
    {
        $buildDate = new DateTime();
        $buildDate->modify("-10 year");
        $carData = ['model' => 'Focus', 'make' => 'Ford', 'color' => 'white', 'buildDate' => $buildDate->format('Y-m-d')];

        $this->client->request('POST', '/cars', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($carData));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
