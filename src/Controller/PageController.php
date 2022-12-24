<?php

namespace App\Controller;

use App\Form\Type\WeatherForecastType;
use App\Factory\WeatherApiFactory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        try {
            $openWeatherApiFactory = WeatherApiFactory::create('openweather', $this->getParameter('app.openweather_api_key'));
            $weatherApiFactory = WeatherApiFactory::create('weatherapi', $this->getParameter('app.weather_api_key'));
        } catch (\Exception $e) {
            throw new \Exception('Error while loading APIs.');
        }

        $form = $this->createForm(WeatherForecastType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $entityManager = $doctrine->getManager();

            $openWeatherApiService = $openWeatherApiFactory->getCurrentWeather($data['city'], $data['country']);

            $weatherApiService = $weatherApiFactory->getCurrentWeather($data['city'], $data['country']);

            $averageTemp = ($openWeatherApiService->getTemperature() + $weatherApiService->getTemperature()) / 2;

            $entityManager->persist($openWeatherApiService);
            $entityManager->persist($weatherApiService);
            $entityManager->flush();
        }

        return $this->render('index.html.twig', [
            'form' => $form,
            'averageTemp' => isset($averageTemp) ? $averageTemp : null,
        ]);
    }
}
