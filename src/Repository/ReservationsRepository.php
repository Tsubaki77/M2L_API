<?php

namespace App\Repository;

use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservations>
 */
class ReservationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }
    
    public function findByAdherent(int $adherentId): array
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.adherent = :id')
        ->setParameter('id', $adherentId)
        ->getQuery()
        ->getResult();
}

    /** @return array<int, array{id: int, nom: string, total: int}> Nombre de réservations par ligue */
    public function countByLigue(): array
    {
        $rows = $this->createQueryBuilder('r')
            ->select('l.id AS id, l.nom AS nom, COUNT(r.id) as total')
            ->join('r.adherent', 'a')
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

    /** @return array<int, array{id: int, nom: string, prenom: string, total: int}> Nombre de réservations par adhérent */
    public function countByAdherent(): array
    {
        $rows = $this->createQueryBuilder('r')
            ->select('a.id_adherent AS id, a.nom AS nom, a.prenom AS prenom, COUNT(r.id) as total')
            ->join('r.adherent', 'a')
            ->groupBy('a.id_adherent')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(
            fn (array $row) => [
                'id'     => (int) $row['id'],
                'nom'    => $row['nom'],
                'prenom' => $row['prenom'],
                'total'  => (int) $row['total'],
            ],
            $rows
        );
    }

//    /**
//     * @return Reservations[] Returns an array of Reservations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservations
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
