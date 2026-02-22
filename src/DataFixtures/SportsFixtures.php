<?php

namespace App\DataFixtures;

use App\Entity\Sports;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SportsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sportsData = [
            [
                'nom' => 'Football',
            ],
            [
                'nom' => 'Basketball',
            ],
            [
                'nom' => 'Tennis',
            ],
            [
                'nom' => 'Natation',
            ],
            [
                'nom' => 'Handball',
            ],
        ];

        foreach ($sportsData as $data) {
            $sport = new Sports();
            $sport->setNom($data['nom']);

            $manager->persist($sport);
        }

        $manager->flush();
    }
}