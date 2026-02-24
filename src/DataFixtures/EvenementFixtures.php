<?php

namespace App\DataFixtures;

use App\Entity\Evenement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EvenementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $evenementsData = [
            [
                'nom' => 'Tournoi Inter-Ligue 2026',
            ],
            [
                'nom' => 'Gala de charité annuel',
            ],
            [
                'nom' => 'Journée Portes Ouvertes',
            ],
            [
                'nom' => 'Championnat Régional',
            ],
        ];

        foreach ($evenementsData as $data) {
            $evenement = new Evenement();
            $evenement->setNom($data['nom']);

            $manager->persist($evenement);
        }

        $manager->flush();
    }
}