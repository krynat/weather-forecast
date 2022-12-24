<?php

namespace App\Factory;

use App\Service\OpenWeatherApiService;
use App\Service\WeatherApiService;
use App\Service\WeatherServiceInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class WeatherApiFactory
 * 
 * @package App\Factory
 */
class WeatherApiFactory
{
    /**
     * Create a WeatherServiceInterface instance.
     * 
     * @param string $serviceName
     * @param string $apiKey
     * @param AdapterInterface|null $cache
     * @param bool $cacheEnabled
     * 
     * @return WeatherServiceInterface
     * 
     * @throws \InvalidArgumentException
     */
    public static function create(string $serviceName, string $apiKey, ?AdapterInterface $cache = null, bool $cacheEnabled = false): WeatherServiceInterface
    {
        switch ($serviceName) {
            case 'openweather':
                return new OpenWeatherApiService($apiKey, $cache, $cacheEnabled);
            case 'weatherapi':
                return new WeatherApiService($apiKey, $cache, $cacheEnabled);
            default:
                throw new \InvalidArgumentException('Invalid API: ' . $serviceName);
        }
    } 
}
