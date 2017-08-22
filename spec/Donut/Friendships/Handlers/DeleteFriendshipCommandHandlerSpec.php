<?php

namespace spec\Angelov\Donut\Friendships\Handlers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Friendships\Commands\DeleteFriendshipCommand;
use Angelov\Donut\Friendships\Events\FriendshipWasDeletedEvent;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Handlers\DeleteFriendshipCommandHandler;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;

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
