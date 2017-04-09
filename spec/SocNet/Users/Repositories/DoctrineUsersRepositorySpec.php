<?php

namespace spec\SocNet\Users\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Users\Repositories\DoctrineUsersRepository;
use PhpSpec\ObjectBehavior;
use SocNet\Users\Repositories\UsersRepositoryInterface;

class DoctrineUsersRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineUsersRepository::class);
    }

    function it_is_a_users_repository()
    {
        $this->shouldImplement(UsersRepositoryInterface::class);
    }
}
