<?php

namespace App\DataFixtures;

use App\Entity\Ligue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LigueFixtures extends Fixture
{
    public const LIGUE_FOOTBALL = 'ligue_football';
    public const LIGUE_BASKETBALL = 'ligue_basketball';
    public const LIGUE_HANDBALL = 'ligue_handball';

    public function load(ObjectManager $manager): void
    {
        $ligueFootball = new Ligue();
        $ligueFootball->setNom('Ligue Lorraine de Football');
        $manager->persist($ligueFootball);
        $this->addReference(self::LIGUE_FOOTBALL, $ligueFootball);

        $ligueBasketball = new Ligue();
        $ligueBasketball->setNom('Ligue Lorraine de Basketball');
        $manager->persist($ligueBasketball);
        $this->addReference(self::LIGUE_BASKETBALL, $ligueBasketball);

        $ligueHandball = new Ligue();
        $ligueHandball->setNom('Ligue Lorraine de Handball');
        $manager->persist($ligueHandball);
        $this->addReference(self::LIGUE_HANDBALL, $ligueHandball);

        $manager->flush();
    }
}
