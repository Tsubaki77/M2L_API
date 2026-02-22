<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdherentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $adherentsData = [
            [
                'nom' => 'Martin',
                'prenom' => 'Lucas',
                'email' => 'lucas.martin@example.com',
                'password' => 'user123',
                'ligue' => 'Ligue Île-de-France',
                'roles' => ['ROLE_USER'],
            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Julie',
                'email' => 'julie.b@example.com',
                'password' => 'password456',
                'ligue' => 'Ligue Occitanie',
                'roles' => ['ROLE_ADMIN'],
            ],
        ];

        foreach ($adherentsData as $data) {
            $adherent = new Adherent();
            $adherent->setNom($data['nom']);
            $adherent->setPrenom($data['prenom']);
            $adherent->setEmail($data['email']);
            $adherent->setMotDePasse($data['password']);
            $adherent->setLigue($data['ligue']);
            $adherent->setRoles($data['roles']);

            $manager->persist($adherent);
        }

        $manager->flush();
    }
}