<?php

namespace spec\Angelov\Donut\Friendships\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\Events\FriendshipWasCreatedEvent;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Handlers\StoreFriendshipCommandHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;
use Angelov\Donut\Users\User;

class StoreFriendshipCommandHandlerSpec extends ObjectBehavior
{
    const FRIENDSHIP_ID = 'uuid value';

    function let(FriendshipsRepositoryInterface $friendships, EventBusInterface $eventBus)
    {
        $this->beConstructedWith($friendships, $eventBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreFriendshipCommandHandler::class);
    }

    function it_stores_friendships(
        StoreFriendshipCommand $command,
        FriendshipsRepositoryInterface $friendships,
        User $friend,
        User $user,
        EventBusInterface $eventBus
    ) {
        $command->getId()->shouldBeCalled()->willReturn(self::FRIENDSHIP_ID);
        $command->getUser()->shouldBeCalled()->willReturn($user);
        $command->getFriend()->shouldBeCalled()->willReturn($friend);

        $friendships->store(Argument::type(Friendship::class))->shouldBeCalled();
        $eventBus->fire(Argument::type(FriendshipWasCreatedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
