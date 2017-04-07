<?php

namespace spec\SocNet\Communities\Commands;

use AppBundle\Entity\User;
use SocNet\Communities\Commands\StoreCommunityCommand;
use PhpSpec\ObjectBehavior;

class StoreCommunityCommandSpec extends ObjectBehavior
{
    const COMMUNITY_NAME = 'Example';
    const COMMUNITY_DESCRIPTION = 'Just an example community';

    function let(User $author)
    {
        $name = self::COMMUNITY_NAME;
        $description = self::COMMUNITY_DESCRIPTION;

        $this->beConstructedWith($name, $author, $description);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreCommunityCommand::class);
    }

    function it_holds_the_community_name()
    {
        $this->getName()->shouldReturn('Example');
    }

    function it_holds_the_community_author(User $author)
    {
        $this->getAuthor()->shouldReturn($author);
    }

    function it_holds_the_community_description()
    {
        $this->getDescription()->shouldReturn(self::COMMUNITY_DESCRIPTION);
    }
}
