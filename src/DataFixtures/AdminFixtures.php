<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $adminsData = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'password' => 'admin123',
            ],
            [
                'nom' => 'Durand',
                'prenom' => 'Marie',
                'password' => 'secure_pass',
            ],
        ];

        foreach ($adminsData as $data) {
            $admin = new Admin();
            $admin->setNom($data['nom']);
            $admin->setPrenom($data['prenom']);
            $admin->setMotDePasse($data['password']);

            $manager->persist($admin);
        }

        $manager->flush();
    }
}