<?php

namespace App\Service\Category;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;

class CategoryService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getAllCategories(): array
    {
        $categoryRepository = $this->doctrine->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'image' => $category->getImage()
            ];
        }

        return $data;
    }

    public function getCategory(int $id): array
    {
        $categoryRepository = $this->doctrine->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new \InvalidArgumentException('La catégorie spécifiée n\'existe pas.');
        }

        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'image' => $category->getImage()
        ];
    }

    public function createCategory(string $name, string $image): array
    {
        if (empty($name) || empty($image)) {
            throw new \InvalidArgumentException('Le nom et l\'image sont requis.');
        }

        $category = new Category();
        $category->setName($name);
        $category->setImage($image);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'image' => $category->getImage()
        ];
    }

    public function updateCategory(int $id, string $name, string $image): array
    {
        $categoryRepository = $this->doctrine->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new \InvalidArgumentException('La catégorie spécifiée n\'existe pas.');
        }

        if (empty($name) || empty($image)) {
            throw new \InvalidArgumentException('Le nom et l\'image sont requis.');
        }

        $category->setName($name);
        $category->setImage($image);

        $entityManager = $this->doctrine->getManager();
        $entityManager->flush();

        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'image' => $category->getImage()
        ];
    }

    public function deleteCategory(int $id): void
    {
        $categoryRepository = $this->doctrine->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new \InvalidArgumentException('La catégorie spécifiée n\'existe pas.');
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
    }
}
