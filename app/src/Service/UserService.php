<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserRepository;
use App\Repository\UserDataRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * User repository.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserData repository.
     *
     * @var UserDataRepository
     */
    private $userDataRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository User repository
     * @param UserDataRepository $userDataRepository UserData repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(UserRepository $userRepository, UserDataRepository $userDataRepository, PaginatorInterface $paginator)
    {
        $this->userDataRepository = $userDataRepository;
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save user.
     *
     * @param User $user User entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
        $this->userDataRepository->save($user->getUserData());
    }

    /**
     * Delete user.
     *
     * @param User $user User entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(User $user): void
    {
        $this->userDataRepository->delete($user->getUserData());
        $this->userRepository->delete($user);
    }

    /**
     * Find user by Id.
     *
     * @param int $id User Id
     *
     * @return User|null User entity
     */
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->findOneById($id);
    }
}