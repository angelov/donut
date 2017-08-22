<?php

namespace Angelov\Donut\Users\EmailAvailabilityChecker;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;

class EmailAvailabilityChecker implements EmailAvailabilityCheckerInterface
{
    private $users;

    public function __construct(UsersRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function isTaken(string $email): bool
    {
        try {
            $this->users->findByEmail($email);
            return true;
        } catch (ResourceNotFoundException $exception) {
            return false;
        }
    }
}
