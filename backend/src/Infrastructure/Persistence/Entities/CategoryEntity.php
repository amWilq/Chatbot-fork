<?php

namespace App\Infrastructure\Persistence\Entities;

use App\Domain\Category\Entities\Category;
use App\Infrastructure\Persistence\Repository\CategoryEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryEntityRepository::class)]
#[ORM\Table(name: 'categories')]
class CategoryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'category_id', type: Types::STRING)]
    private string $id;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    private string $name;

    #[ORM\Column(name: 'icon_url', type: Types::STRING)]
    private string $iconUrl;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    public function setIconUrl(string $iconUrl): void
    {
        $this->iconUrl = $iconUrl;
    }

    public static function fromDomainEntity(Category $category, EntityManagerInterface $entityManager): self
    {
        $categoryEntity = $entityManager->getRepository(CategoryEntity::class)
            ->find($category->getId()->toString(), raw: true);

        if (is_null($categoryEntity)) {
            $categoryEntity = new self();
            $categoryEntity->setId($category->getId()->toString());
        }

        $categoryEntity->setName($category->getName());
        $categoryEntity->setIconUrl($category->getIconUrl());

        return $categoryEntity;
    }
}
