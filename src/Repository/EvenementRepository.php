<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection; 

/**
 * @extends ServiceEntityRepository<Evenement>
 */
class EvenementRepository extends ServiceEntityRepository
{
    private Connection $connection; 
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
        $this->connection = $registry->getConnection(); 
    }
    
    public function findUpcomingEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_debut >= :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('e.date_debut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllRaw(): array
    {
        $sql = "SELECT id, nom, date_debut FROM evenement";
        $result = $this->connection->executeQuery($sql);
        return $result->fetchAllAssociative();
    }

    public function insertRaw(string $nom, string $dateDebut): int
    {
        $sql = "INSERT INTO evenement (nom, date_debut) VALUES (:nom, :dateDebut)";
        
        $this->connection->executeStatement($sql, [
            'nom' => $nom,
            'dateDebut' => $dateDebut,
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function updateRaw(int $id, string $nom): int
    {
        $sql = "UPDATE evenement SET nom = :nom WHERE id = :id";
        
        return $this->connection->executeStatement($sql, [
            'id' => $id,
            'nom' => $nom,
        ]);
    }

    public function deleteRaw(int $id): int
    {
        $sql = "DELETE FROM evenement WHERE id = :id";
        return $this->connection->executeStatement($sql, ['id' => $id]);
    }
//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
