<?php

namespace spec\Angelov\Donut\Friendships\FriendshipsRecorder;

use GraphAware\Neo4j\Client\Client;
use Angelov\Donut\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;
use Angelov\Donut\Friendships\FriendshipsRecorder\Neo4jFriendshipsRecorder;
use PhpSpec\ObjectBehavior;

class Neo4jFriendshipsRecorderSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Neo4jFriendshipsRecorder::class);
    }

    function it_is_friendships_recorder()
    {
        $this->shouldImplement(FriendshipsRecorderInterface::class);
    }
}
