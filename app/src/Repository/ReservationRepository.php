<?php
/**
 * Reservation Repository.
 */

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ReservationRepository.
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 3;

    /**
     * ReservationRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial reservation.{id, createdAt, status}',
                'partial user.{id, email}',
                'partial tape.{id, title}'
            )
            ->join('reservation.author', 'user')
            ->leftJoin('reservation.tape', 'tape')
            ->orderBy('reservation.createdAt', 'DESC');
    }

    /**
     * Query reservations by author.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('reservation.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Reservation $reservation): void
    {
        $this->_em->persist($reservation);
        $this->_em->flush($reservation);
    }

    /**
     * Delete record.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Reservation $reservation): void
    {
        $this->_em->remove($reservation);
        $this->_em->flush($reservation);
    }

     /**
     * Get or create new query builder.
     *
     * @param QueryBuilder $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('reservation');
    }
}
