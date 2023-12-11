<?php

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Language\Entities\Language;
use App\Infrastructure\Persistence\Entities\LanguageEntity;
use App\Infrastructure\Persistence\Repository\CategoryEntityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LanguageFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        protected CategoryEntityRepository $categoryEntityRepository
    ) {
    }

    public const INIT_DATA = [
        [
            'name' => 'javascript',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/javascript.svg',
            'categories' => [
                'frontend',
                'backend',
                'fullstack',
            ],
        ],
        [
            'name' => 'PHP',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/php.svg',
            'categories' => [
                'backend',
            ],
        ],
        [
            'name' => 'C#',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/csharp.svg',
            'categories' => [
                'backend',
                'devops',
            ],
        ],
        [
            'name' => 'C++',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/cpp.svg',
            'categories' => [
                'cyber-security',
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::INIT_DATA as ['name' => $name, 'icon' => $icon, 'categories' => $categories]) {
            $c = [];
            foreach ($categories as $category) {
                $c[] = $this->categoryEntityRepository->mapToDomainEntity(
                    $this->getReference('category_'.$category)
                );
            }
            $language = Language::create(
                name: $name,
                iconUrl: $icon,
                categories: $c
            );
            $manager->persist(
                LanguageEntity::fromDomainEntity($language, $manager)
            );
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
