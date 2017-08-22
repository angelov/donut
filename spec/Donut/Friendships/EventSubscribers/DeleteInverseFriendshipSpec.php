<?php

namespace spec\Angelov\Donut\Friendships\EventSubscribers;

use Angelov\Donut\Friendships\Events\FriendshipWasDeletedEvent;
use Angelov\Donut\Friendships\EventSubscribers\DeleteInverseFriendship;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;
use Angelov\Donut\Users\User;

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
