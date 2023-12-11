<?php

namespace App\Infrastructure\Persistence\Entities;

use App\Domain\Language\Entities\Language;
use App\Infrastructure\Persistence\Repository\LanguageEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguageEntityRepository::class)]
#[ORM\Table(name: 'languages')]
class LanguageEntity implements PersistenceEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'language_id', type: Types::STRING)]
    private string $id;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    private string $name;

    #[ORM\Column(name: 'icon_url', type: Types::STRING)]
    private string $iconUrl;

    #[ORM\JoinTable(name: 'languages_categories')]
    #[ORM\JoinColumn(name: 'language_id', referencedColumnName: 'language_id')]
    #[ORM\InverseJoinColumn(name: 'category_id', referencedColumnName: 'category_id')]
    #[ORM\ManyToMany(targetEntity: CategoryEntity::class)]
    private Collection $categories;

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

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(CategoryEntity $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(CategoryEntity $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    private function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public static function fromDomainEntity(Language $language, EntityManagerInterface $entityManager): self
    {
        $languageEntity = $entityManager->getRepository(LanguageEntity::class)
            ->find($language->getId()->toString(), raw: true);

        if (!$languageEntity) {
            $languageEntity = new self();
            $languageEntity->setId($language->getId()->toString());
        }

        $languageEntity->setName($language->getName());
        $languageEntity->setIconUrl($language->getIconUrl());
        foreach ($language->getCategories() as $category) {
            $languageEntity->addCategory(CategoryEntity::fromDomainEntity($category, $entityManager));
        }

        return $languageEntity;
    }
}
