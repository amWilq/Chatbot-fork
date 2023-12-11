<?php

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Category\Entities\Category;
use App\Infrastructure\Persistence\Entities\CategoryEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private const INIT_DATA = [
        [
            'name' => 'frontend',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/frontend.svg',
        ],
        [
            'name' => 'backend',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/backend.svg',
        ],
        [
            'name' => 'fullstack',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/fullstack.svg',
        ],
        [
            'name' => 'cyber-security',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/cyber-security.svg',
        ],
        [
            'name' => 'devops',
            'icon' => 'https://codeperium-chatbot.s3.eu-west-1.amazonaws.com/assets/devops.svg',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::INIT_DATA as ['name' => $name, 'icon' => $iconUrl]) {
            $category = Category::create(
                name: $name,
                iconUrl: $iconUrl,
            );
            $manager->persist(
                CategoryEntity::fromDomainEntity($category, $manager)
            );

            $this->addReference(
                'category_'.$name,
                CategoryEntity::fromDomainEntity($category, $manager)
            );
        }
        $manager->flush();
    }
}
