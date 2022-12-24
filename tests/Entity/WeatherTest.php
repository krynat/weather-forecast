<?php

namespace App\Tests\Entity;

use App\Entity\Weather;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $weather = new Weather();
        $weather->setCity('Krakow');
        $weather->setCountry('Polska');
        $weather->setLatitude(50.666);
        $weather->setLongitude(19.23);
        $weather->setTemperature(3.5);
        $weather->setTimestamp(new \DateTime());

        $this->assertEquals('Krakow', $weather->getCity());
        $this->assertEquals('Polska', $weather->getCountry());
        $this->assertEquals(50.666, $weather->getLatitude());
        $this->assertEquals(19.23, $weather->getLongitude());
        $this->assertEquals(3.5, $weather->getTemperature());
        $this->assertInstanceOf(\DateTimeInterface::class, $weather->getTimestamp());
    }
}
