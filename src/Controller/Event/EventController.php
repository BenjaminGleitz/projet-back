<?php

namespace App\Controller\Event;

use App\Service\Event\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/event', name: 'app_event_')]
class EventController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EventService $eventService): JsonResponse
    {
        $events = $eventService->getAllEvents();
        return $this->json($events);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(EventService $eventService, int $id): JsonResponse
    {
        $event = $eventService->getEvent($id);
        return $this->json($event);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, EventService $eventService): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $newEvent = $eventService->createEvent($requestData);
        return $this->json($newEvent, 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(EventService $eventService, int $id): JsonResponse
    {
        $eventService->updateEvent($id);
        return $this->json([]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(EventService $eventService, int $id): JsonResponse
    {
        $eventService->deleteEvent($id);
        return $this->json([], 204);
    }
}
