<?php

namespace App\Controller\Country;

use App\Service\Country\CountryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/country', name: 'app_country_', methods: ['GET', 'POST', 'PUT', 'DELETE'])]
class CountryController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CountryService $countryService): JsonResponse
    {
        $countries = $countryService->getAllCountries();
        return $this->json($countries);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(CountryService $countryService, int $id): JsonResponse
    {
        $country = $countryService->getCountry($id);
        return $this->json($country);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, CountryService $countryService): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $newCountry = $countryService->createCountry($requestData['name']);
        return $this->json($newCountry, 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, CountryService $countryService, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $countryService->updateCountry($id, $requestData['name']);
        return $this->json([]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(CountryService $countryService, int $id): JsonResponse
    {
        $countryService->deleteCountry($id);
        return $this->json([], 204);
    }
}
