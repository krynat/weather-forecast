<?php

namespace App\Service;

use App\Entity\Weather;
use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class OpenWeatherApiService
 * 
 * @package App\Service
 */
class OpenWeatherApiService implements WeatherServiceInterface
{
    /**
     * @var string $apiUrl
     */
    private $apiUrl = 'https://api.openweathermap.org/data/2.5/';

    /**
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * @var AdapterInterface|null $cache
     */
    private $cache;

    /**
     * @var bool $cacheEnabled
     */
    private $cacheEnabled;

    /**
     * OpenWeatherApiService constructor.
     * 
     * @param string $apiKey
     * @param AdapterInterface|null $cache
     * @param bool $cacheEnabled
     */
    public function __construct(string $apiKey, ?AdapterInterface $cache = null, bool $cacheEnabled = false)
    {
        $this->apiKey = $apiKey;
        $this->cache = $cache;
        $this->cacheEnabled = $cacheEnabled;
    }

    /**
     * Gets data from the OpenWeatherAPI.
     * 
     * @param string $endpoint
     * @param array $params
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public function getData(string $endpoint, array $params = []): array
    {
        try {
            if ($this->cacheEnabled && $this->cache instanceof AdapterInterface) {
                $cacheKey = $this->apiUrl . '-' . $endpoint . '-' . implode('-', $params);
                $item = $this->cache->getItem($cacheKey);

                if ($item->isHit()) {
                    return $item->get();
                }
            }

            $client = new Client([
                'base_uri' => $this->apiUrl,
            ]);

            $params['appid'] = $this->apiKey;

            $response = $client->get($endpoint, [
                'query' => $params,
            ]);

            $data = json_decode($response->getBody(), true);

            if ($this->cacheEnabled && $this->cache instanceof AdapterInterface) {
                $item->set($data);
                $this->cache->save($item);
            }

            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get the current weather for a city.
     * 
     * @param string $city
     * @param string|null $country
     * 
     * @return Weather
     * 
     * @throws \Exception
     */
    public function getCurrentWeather(string $city, ?string $country = null): Weather
    {
        $query = $country ? $city . ',' . $country : $city;

        $data = $this->getData('weather', [
            'q' => $query,
            'units' => 'metric',
        ]);

        $weather = new Weather();
        $weather->setCity($city);
        $weather->setCountry($country);
        $weather->setLatitude($data['coord']['lat']);
        $weather->setLongitude($data['coord']['lon']);
        $weather->setTemperature(round($data['main']['temp'], 1));
        $weather->setTimestamp(new \DateTime());

        return $weather;
    }
}
