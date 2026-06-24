<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use App\Entity\Ligue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdherentFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Ligue $ligue */
        $ligue = $this->getReference(LigueFixtures::LIGUE_FOOTBALL, Ligue::class);

        $adherent = new Adherent();

        $adherent->setNumeroAdherent('ADH2026001');
        $adherent->setNom('GHEZ');
        $adherent->setPrenom('Cam');
        $adherent->setEmail('camghez77@gmail.com');
        $adherent->setLigue($ligue);
        $adherent->setPoste('Coach');
        $adherent->setMotDePasse(
            $this->hasher->hashPassword($adherent, 'KingInTheNorth2')
        );

        $manager->persist($adherent);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [LigueFixtures::class];
    }
}
