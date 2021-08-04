<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function getOneAsArray(int $id): ?array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where('c.id =:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function getAllAsArray(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->orderBy('c.make', 'ASC')->addOrderBy('c.model', 'ASC')->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function store(Car $car): Car
    {
        $this->getEntityManager()->persist($car);
        $this->getEntityManager()->flush();

        return $car;
    }

    public function remove(Car $car): void
    {
        $this->getEntityManager()->remove($car);
        $this->getEntityManager()->flush();
    }
}
