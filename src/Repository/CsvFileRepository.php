<?php

namespace App\Repository;

use App\Entity\CsvFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CsvFile>
 *
 * @method CsvFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsvFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsvFile[]    findAll()
 * @method CsvFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsvFile::class);
    }

    public function save(CsvFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CsvFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
