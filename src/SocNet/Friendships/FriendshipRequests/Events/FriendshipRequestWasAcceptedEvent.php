<?php

namespace SocNet\Friendships\FriendshipRequests\Events;

use JMS\Serializer\Annotation as Serializer;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

class FriendshipRequestWasAcceptedEvent
{
    /**
     * @Serializer\Type(name="SocNet\Friendships\FriendshipRequests\FriendshipRequest")
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
