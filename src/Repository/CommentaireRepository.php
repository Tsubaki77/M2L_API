<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection; 

/**
 * @extends ServiceEntityRepository<Commentaire>
 */
class CommentaireRepository extends ServiceEntityRepository
{
    private Connection $connection; 

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
        $this->connection = $registry->getConnection(); 
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAllRaw(): array
    {
        $sql = "SELECT id, contenu, created_at FROM commentaire ORDER BY created_at DESC";
        $result = $this->connection->executeQuery($sql);
        return $result->fetchAllAssociative();
    }

    public function insertRaw(string $contenu): int
    {
        $sql = "INSERT INTO commentaire (contenu, created_at) VALUES (:contenu, :createdAt)";
        
        $this->connection->executeStatement($sql, [
            'contenu' => $contenu,
            'createdAt' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function updateRaw(int $id, string $contenu): int
    {
        $sql = "UPDATE commentaire SET contenu = :contenu WHERE id = :id";

        return $this->connection->executeStatement($sql, [
            'id' => $id,
            'contenu' => $contenu,
        ]);
    }

    public function deleteRaw(int $id): int
    {
        $sql = "DELETE FROM commentaire WHERE id = :id";
        return $this->connection->executeStatement($sql, ['id' => $id]);
    }

//    /**
//     * @return Commentaire[] Returns an array of Commentaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commentaire
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
