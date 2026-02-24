<?php

namespace App\DataFixtures;

use App\Entity\Salles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SallesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sallesData = [
            [
                'nom' => 'Gymnase Central',
                'adresse' => '10 rue du Sport, Paris',
                'capacite' => '50',
                'type' => 'Multisport',
                'description' => 'Un grand gymnase avec accès handicapé.',
            ],
            [
                'nom' => 'Salle de Yoga',
                'adresse' => '5 avenue Zen, Lyon',
                'capacite' => '15',
                'type' => 'Bien-être',
                'description' => 'Ambiance calme avec tapis fournis.',
            ],
        ];

        foreach ($sallesData as $data) {
            $salle = new Salles();
            $salle->setNom($data['nom']);
            $salle->setAdresse($data['adresse']);
            $salle->setCapacite($data['capacite']);
            $salle->setType($data['type']);
            $salle->setDescription($data['description']);

            $manager->persist($salle);
        }

        $manager->flush();
    }
}