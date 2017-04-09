<?php

namespace AppBundle\Repository;

use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\User;
use AppBundle\Exceptions\ResourceNotFoundException;
use Doctrine\ORM\EntityRepository;

class DoctrineUsersRepository implements UsersRepositoryInterface
{
    private $baseRepository;

    public function __construct(EntityRepository $baseRepository)
    {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @inheritdoc
     */
    public function find(int $id): User
    {
        /** @var \SocNet\Users\User|null $user */
        $user = $this->baseRepository->find($id);

        if ($user) {
            return $user;
        }

        throw new ResourceNotFoundException(sprintf(
            'Could not find an User with the given id [%d]',
            $id
        ));
    }

    public function all(): array
    {
        return $this->baseRepository->findAll();
    }

    public function getByEmail(string $email): User
    {
        /** @var \SocNet\Users\User|null $user */
        $user = $this->baseRepository->findOneBy(['email' => $email]);

        if ($user) {
            return $user;
        }

        throw new ResourceNotFoundException(sprintf(
            'Could not found an User with the given e-mail address [%s]',
            $email
        ));
    }
}
