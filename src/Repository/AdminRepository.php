<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection; 

/**
 * @extends ServiceEntityRepository<Admin>
 */
class AdminRepository extends ServiceEntityRepository
{
    private Connection $connection; 

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
        $this->connection = $registry->getConnection(); 
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.roles LIKE :val')
            ->setParameter('val', '%' . $role . '%')
            ->getQuery()
            ->getResult();
    }

    public function findAllRaw(): array
    {
        $sql = "SELECT id, email, roles FROM admin";
        $result = $this->connection->executeQuery($sql);
        return $result->fetchAllAssociative();
    }

    public function insertRaw(string $email, string $password, string $roles): int
    {
        $sql = "INSERT INTO admin (email, password, roles) VALUES (:email, :password, :roles)";

        $this->connection->executeStatement($sql, [
            'email' => $email,
            'password' => $password, 
            'roles' => $roles,
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function updateRaw(int $id, string $email): int
    {
        $sql = "UPDATE admin SET email = :email WHERE id = :id";

        return $this->connection->executeStatement($sql, [
            'id' => $id,
            'email' => $email,
        ]);
    }

    public function deleteRaw(int $id): int
    {
        $sql = "DELETE FROM admin WHERE id = :id";
        return $this->connection->executeStatement($sql, ['id' => $id]);
    }
}
//    /**
//     * @return Admin[] Returns an array of Admin objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Admin
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
