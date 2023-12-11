<?php

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Infrastructure\Persistence\Entities\AssessmentTypeEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AssessmentTypeFixtures extends Fixture
{
    public const INIT_DATA = [
        [
            'name' => 'quiz',
            'description' => 'A quick and informal assessment of student knowledge.',
            'difficulties' => [
                'beginner',
            ],
        ],
        [
            'name' => 'multiple-choice',
            'description' => 'An assessment item consisting of a stem, which poses the question or problem, followed by a list of possible responses.',
            'difficulties' => [
                'beginner',
            ],
        ],
        [
            'name' => 'free-text',
            'description' => 'An open-ended question that is also known as essay, free format, or comments.',
            'difficulties' => [
                'beginner',
                'intermediate',
                'advanced',
            ],
        ],
        [
            'name' => 'code-snippet',
            'description' => 'Small blocks of code will be presented with purpose to find errors or to complete the code.',
            'difficulties' => [
                'intermediate',
                'advanced',
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::INIT_DATA as ['name' => $name, 'description' => $description, 'difficulties' => $difficulties]) {
            $assessmentType = new AssessmentType(
                id: null,
                formatName: $name,
            );
            $manager->persist(
                AssessmentTypeEntity::fromDomainEntity($assessmentType, $manager)
            );
        }
        $manager->flush();
    }
}