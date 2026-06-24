<?php

namespace App\Repository;

use App\Entity\Adherent;
use App\Entity\Ligue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adherent>
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    public function findOneByEmail(string $email): ?Adherent
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByNumeroAdherent(string $numero): ?Adherent
    {
        return $this->findOneBy(['numero_adherent' => $numero]);
    }

    /** @return Adherent[] */
    public function findByLigue(Ligue $ligue): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.ligue = :ligue')
            ->setParameter('ligue', $ligue)
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /** @return Adherent[] */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom LIKE :val OR a.prenom LIKE :val')
            ->setParameter('val', '%' . $name . '%')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /** @return array<int, array{id: int, nom: string, total: int}> Nombre d'adhérents par ligue */
    public function countByLigue(): array
    {
        $rows = $this->createQueryBuilder('a')
            ->select('l.id AS id, l.nom AS nom, COUNT(a.id_adherent) as total')
            ->join('a.ligue', 'l')
            ->groupBy('l.id')
            ->orderBy('l.nom', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(
            fn (array $row) => ['id' => (int) $row['id'], 'nom' => $row['nom'], 'total' => (int) $row['total']],
            $rows
        );
    }
}
