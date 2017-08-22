<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Events;

use JMS\Serializer\Annotation as Serializer;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;

class FriendshipRequestWasAcceptedEvent
{
    /**
     * @Serializer\Type(name="Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest")
     */
    private $request;

    public function __construct(FriendshipRequest $request)
    {
        $this->request = $request;
    }

    public function getFriendshipRequest() : FriendshipRequest
    {
        return $this->request;
    }
}
