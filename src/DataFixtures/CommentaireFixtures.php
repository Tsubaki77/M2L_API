<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $commentairesData = [
            [
                'contenu' => 'Très bonne salle, bien éclairée.',
                'note' => '4/5',
                'type' => 'Avis Salle',
            ],
            [
                'contenu' => 'L\'événement était super bien organisé !',
                'note' => '5/5',
                'type' => 'Feedback Événement',
            ],
        ];

        foreach ($commentairesData as $data) {
            $commentaire = new Commentaire();
            $commentaire->setContenu($data['contenu']);
            $commentaire->setNote($data['note']);
            $commentaire->setType($data['type']);
            // La date et createdAt sont gérés par onPrePersist dans l'entité

            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}
