<?php

namespace spec\SocNet\Friendships\Handlers;

use PhpSpec\ObjectBehavior;
use SocNet\Friendships\Commands\DeleteFriendshipCommand;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\Handlers\DeleteFriendshipCommandHandler;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;

class DeleteFriendshipCommandHandlerSpec extends ObjectBehavior
{
    public function let(FriendshipsRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteFriendshipCommandHandler::class);
    }

    function it_handles_delete_friendship_commands(DeleteFriendshipCommand $command, Friendship $friendship, FriendshipsRepositoryInterface $repository)
    {
        $command->getFriendship()->willReturn($friendship);

        $repository->destroy($friendship)->shouldBeCalled();

        $this->handle($command);
    }
}
