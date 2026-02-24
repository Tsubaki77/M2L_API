<?php

namespace App\DataFixtures;

use App\Entity\Reservations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReservationsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $reservationsData = [
            [
                'dateDebut' => new \DateTime('2024-05-10'),
                'dateFin' => new \DateTime('2024-05-10'),
                'heureDebut' => new \DateTime('14:00:00'),
                'heureFin' => new \DateTime('16:00:00'),
                'motif' => 'Entraînement de Basket',
                'statut' => 'Validé',
            ],
            [
                'dateDebut' => new \DateTime('2024-05-12'),
                'dateFin' => new \DateTime('2024-05-12'),
                'heureDebut' => new \DateTime('10:00:00'),
                'heureFin' => new \DateTime('12:00:00'),
                'motif' => 'Réunion Ligue',
                'statut' => 'En attente',
            ],
        ];

        foreach ($reservationsData as $data) {
            $res = new Reservations();
            $res->setDateDebut($data['dateDebut']);
            $res->setDateFin($data['dateFin']);
            $res->setHeureDebut($data['heureDebut']);
            $res->setHeureFin($data['heureFin']);
            $res->setMotif($data['motif']);
            $res->setStatut($data['statut']);

            $manager->persist($res);
        }

        $manager->flush();
    }
}