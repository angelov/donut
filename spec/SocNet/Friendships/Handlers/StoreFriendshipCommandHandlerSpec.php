<?php

namespace spec\SocNet\Friendships\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Friendships\Commands\StoreFriendshipCommand;
use SocNet\Friendships\Events\FriendshipWasCreatedEvent;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\Handlers\StoreFriendshipCommandHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;
use SocNet\Users\User;

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
