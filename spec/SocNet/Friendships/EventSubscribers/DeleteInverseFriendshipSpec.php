<?php

namespace spec\SocNet\Friendships\EventSubscribers;

use SocNet\Friendships\Events\FriendshipWasDeletedEvent;
use SocNet\Friendships\EventSubscribers\DeleteInverseFriendship;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\Repositories\FriendshipsRepositoryInterface;
use SocNet\Users\User;

class DeleteInverseFriendshipSpec extends ObjectBehavior
{
    function let(FriendshipsRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteInverseFriendship::class);
    }

    function it_deletes_inverse_friendships(
        FriendshipWasDeletedEvent $event,
        Friendship $friendship,
        User $first,
        User $second,
        FriendshipsRepositoryInterface $repository,
        Friendship $inverse
    ) {
        $event->getFriendship()->willReturn($friendship);

        $friendship->getUser()->willReturn($first);
        $friendship->getFriend()->willReturn($second);

        $repository->findBetweenUsers($first, $second)->willReturn([$inverse]);

        $repository->destroy($inverse)->shouldBeCalled();

        $this->notify($event);
    }
}
