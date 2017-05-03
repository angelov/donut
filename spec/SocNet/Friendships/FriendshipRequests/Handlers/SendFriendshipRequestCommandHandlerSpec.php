<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Handlers;

use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Handlers\SendFriendshipRequestCommandHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use SocNet\Users\User;

class SendFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    function let(FriendshipRequestsRepositoryInterface $repository, UuidGeneratorInterface $uuidGenerator)
    {
        $this->beConstructedWith($repository, $uuidGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendFriendshipRequestCommandHandler::class);
    }

    function it_stores_new_friendship_requests(
        SendFriendshipRequestCommand $command,
        User $sender,
        User $recipient,
        FriendshipRequestsRepositoryInterface $repository,
        UuidGeneratorInterface $uuidGenerator
    ) {
        $command->getRecipient()->willReturn($recipient);
        $command->getSender()->willReturn($sender);

        $uuidGenerator->generate()->willReturn('uuid value');

        $repository->store(Argument::type(FriendshipRequest::class))->shouldBeCalled();

        $this->handle($command);
    }
}
