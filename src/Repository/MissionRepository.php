<?php

namespace App\Repository;

use App\Data\Search;
use App\Entity\Mission;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Mission>
 *
 * @method Mission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mission[]    findAll()
 * @method Mission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

    public function save(Mission $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Mission $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupére les missions en lien avec la recherche
     * @return Mission[] 
     */

    public function findSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('m')
            ->select('t', 'm')
            ->join('m.type', 't')

            ->select('s', 'm')
            ->join('m.status', 's');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('m.title LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->status)) { {
                $query = $query
                    ->andWhere('s.id IN (:status)')
                    ->setParameter('status', $search->status);
            }
        }

        if (!empty($search->type)) { {
                $query = $query
                    ->andWhere('t.id IN (:type)')
                    ->setParameter('type', $search->type);
            }
        }
        return $query->getQuery();
        // $query = $query->getQuery();
        // return $this->paginator->paginate(
        //     $query,
        //     $search->page,
        //     3
        // );
    }


//    /**
//     * @return Mission[] Returns an array of Mission objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mission
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
