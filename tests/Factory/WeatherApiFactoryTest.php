<?php

namespace App\Tests\Factory;

use App\Factory\WeatherApiFactory;
use App\Service\OpenWeatherApiService;
use App\Service\WeatherApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class WeatherApiFactoryTest extends TestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf(
            OpenWeatherApiService::class,
            WeatherApiFactory::create('openweather', 'API_KEY', null, false)
        );

        $this->assertInstanceOf(
            OpenWeatherApiService::class,
            WeatherApiFactory::create('openweather', 'API_KEY', $this->createMock(AdapterInterface::class), true)
        );

        $this->assertInstanceOf(
            WeatherApiService::class,
            WeatherApiFactory::create('weatherapi', 'API_KEY', null, false)
        );

        $this->assertInstanceOf(
            WeatherApiService::class,
            WeatherApiFactory::create('weatherapi', 'API_KEY', $this->createMock(AdapterInterface::class), true)
        );

        $this->expectException(\InvalidArgumentException::class);
        WeatherApiFactory::create('invalidapi', 'API_KEY', null, false);
    }
}