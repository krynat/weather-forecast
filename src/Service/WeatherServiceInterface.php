<?php

namespace App\Service;

use App\Entity\Weather;

/**
 * Interface WeatherServiceInterface
 * 
 * @package App\Service
 */
interface WeatherServiceInterface
{
    /**
     * Gets data from the API.
     * 
     * @param string $endpoint
     * @param array $params
     * 
     * @return array
     */
    public function getData(string $endpoint, array $params = []): array;

    /**
     * Get the current weather data for a city.
     * 
     * @param string $city
     * @param string|null $country
     * 
     * @return Weather
     */
    public function getCurrentWeather(string $city, ?string $country = null): Weather;
}
