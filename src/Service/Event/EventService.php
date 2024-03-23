<?php

namespace App\Service\Event;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class EventService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getAllEvents(): array
    {
        $eventRepository = $this->doctrine->getRepository(Event::class);
        $events = $eventRepository->findAll();

        $data = [];

        foreach ($events as $event) {
            $creatorId = $event->getCreator() ? $event->getCreator()->getId() : null;

            $data[] = [
                'id' => $event->getId(),
                'city' => $event->getCity()->getName(),
                'category' => $event->getCategory()->getName(),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'status' => $event->getStatus(),
                'creator' => $creatorId,
                'start_at' => $event->getStartAt()->format('Y-m-d H:i:s'),
                'created_at' => $event->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $event->getUpdatedAt() ? $event->getUpdatedAt()->format('Y-m-d H:i:s') : null
            ];
        }

        return $data;
    }

    public function getEvent(int $id): array
    {
        $eventRepository = $this->doctrine->getRepository(Event::class);
        $event = $eventRepository->find($id);

        if (!$event) {
            throw new \InvalidArgumentException('L\'événement spécifié n\'existe pas.');
        }

        $creatorId = $event->getCreator() ? $event->getCreator()->getId() : null;

        return [
            'id' => $event->getId(),
            'city' => $event->getCity()->getName(),
            'category' => $event->getCategory()->getName(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'status' => $event->getStatus(),
            'creator' => $creatorId,
            'start_at' => $event->getStartAt()->format('Y-m-d H:i:s'),
            'created_at' => $event->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $event->getUpdatedAt() ? $event->getUpdatedAt()->format('Y-m-d H:i:s') : null
        ];
    }

    public function createEvent(int $cityId, int $categoryId, string $title, string $description, string $status, int $creatorId, string $startAt): array
    {
        if (empty($title) || empty($description) || empty($status) || empty($startAt)) {
            throw new \InvalidArgumentException('Le titre, la description, le statut et la date de début sont requis.');
        }

        $event = new Event();
        $event->setTitle($title);
        $event->setDescription($description);
        $event->setStatus($status);
        $event->setStartAt(new \DateTimeImmutable($startAt));
        $event->setCreatedAt(new \DateTimeImmutable());
        $event->setCity($this->doctrine->getRepository(City::class)->find($cityId));
        $event->setCategory($this->doctrine->getRepository(Category::class)->find($categoryId));
        $event->setCreator($this->doctrine->getRepository(User::class)->find($creatorId));

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        return [
            'id' => $event->getId(),
            'city' => $event->getCity()->getName(),
            'category' => $event->getCategory()->getName(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'status' => $event->getStatus(),
            'creator' => $event->getCreator()->getId(), // On récupère l'ID du créateur
            'start_at' => $event->getStartAt()->format('Y-m-d H:i:s'),
            'created_at' => $event->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $event->getUpdatedAt() ? $event->getUpdatedAt()->format('Y-m-d H:i:s') : null
        ];
    }

    public function updateEvent(int $id, int $cityId, int $categoryId, string $title, string $description, string $status, string $startAt): array
    {
        $eventRepository = $this->doctrine->getRepository(Event::class);
        $event = $eventRepository->find($id);

        if (!$event) {
            throw new \InvalidArgumentException('L\'événement spécifié n\'existe pas.');
        }

        if (empty($title) || empty($description) || empty($status) || empty($startAt)) {
            throw new \InvalidArgumentException('Le titre, la description, le statut et la date de début sont requis.');
        }

        $event->setTitle($title);
        $event->setDescription($description);
        $event->setStatus($status);
        $event->setStartAt(new \DateTimeImmutable($startAt));
        $event->setCity($this->doctrine->getRepository(City::class)->find($cityId));
        $event->setCategory($this->doctrine->getRepository(Category::class)->find($categoryId));
        $event->setUpdatedAt(new \DateTimeImmutable());

        $entityManager = $this->doctrine->getManager();
        $entityManager->flush();

        $creatorId = $event->getCreator() ? $event->getCreator()->getId() : null;

        return [
            'id' => $event->getId(),
            'city' => $event->getCity()->getName(),
            'category' => $event->getCategory()->getName(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'status' => $event->getStatus(),
            'creator' => $creatorId,
            'start_at' => $event->getStartAt()->format('Y-m-d H:i:s'),
            'created_at' => $event->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $event->getUpdatedAt() ? $event->getUpdatedAt()->format('Y-m-d H:i:s') : null
        ];
    }

    public function deleteEvent(int $id): void
    {
        $eventRepository = $this->doctrine->getRepository(Event::class);
        $event = $eventRepository->find($id);

        if (!$event) {
            throw new \InvalidArgumentException('L\'événement spécifié n\'existe pas.');
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($event);
        $entityManager->flush();
    }
}
