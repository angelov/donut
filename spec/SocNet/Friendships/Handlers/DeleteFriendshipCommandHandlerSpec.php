<?php

namespace spec\SocNet\Friendships\Handlers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Friendships\Commands\DeleteFriendshipCommand;
use SocNet\Friendships\Events\FriendshipWasDeletedEvent;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\Handlers\DeleteFriendshipCommandHandler;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;

class DeleteFriendshipCommandHandlerSpec extends ObjectBehavior
{
    public function let(FriendshipsRepositoryInterface $repository, EventBusInterface $eventBus)
    {
        $this->beConstructedWith($repository, $eventBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteFriendshipCommandHandler::class);
    }

    function it_handles_delete_friendship_commands(
        DeleteFriendshipCommand $command,
        Friendship $friendship,
        FriendshipsRepositoryInterface $repository,
        EventBusInterface $eventBus
    ) {
        $command->getFriendship()->willReturn($friendship);

        $repository->destroy($friendship)->shouldBeCalled();

        $eventBus->fire(Argument::type(FriendshipWasDeletedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
