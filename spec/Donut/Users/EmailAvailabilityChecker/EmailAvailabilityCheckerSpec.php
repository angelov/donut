<?php

namespace spec\Angelov\Donut\Users\EmailAvailabilityChecker;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\EmailAvailabilityChecker\EmailAvailabilityChecker;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;

class EmailAvailabilityCheckerSpec extends ObjectBehavior
{
    const EMAIL_ADDRESS = 'john@example.com';

    function let(UsersRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EmailAvailabilityChecker::class);
    }

    function it_is_email_availability_checker()
    {
        $this->shouldImplement(EmailAvailabilityCheckerInterface::class);
    }

    function it_confirms_that_email_is_taken(UsersRepositoryInterface $repository, User $user)
    {
        $repository->findByEmail(self::EMAIL_ADDRESS)
            ->shouldBeCalled()
            ->willReturn($user);

        $this->isTaken(self::EMAIL_ADDRESS)->shouldReturn(true);
    }

    function it_denies_that_email_is_taken(UsersRepositoryInterface $repository)
    {
        $repository->findByEmail(self::EMAIL_ADDRESS)
            ->shouldBeCalled()
            ->willThrow(ResourceNotFoundException::class);

        $this->isTaken(self::EMAIL_ADDRESS)->shouldReturn(false);
    }
}
