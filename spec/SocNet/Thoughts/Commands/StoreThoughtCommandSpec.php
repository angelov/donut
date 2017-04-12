<?php

namespace spec\SocNet\Thoughts\Commands;

use SocNet\Thoughts\Commands\StoreThoughtCommand;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;

class StoreThoughtCommandSpec extends ObjectBehavior
{
    const THOUGHT_CONTENT = 'something to say';

    public function let(User $author)
    {
        $this->beConstructedWith($author, self::THOUGHT_CONTENT);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreThoughtCommand::class);
    }

    function it_holds_the_author(User $author)
    {
        $this->getAuthor()->shouldReturn($author);
    }

    function it_holds_the_content()
    {
        $this->getContent()->shouldReturn(self::THOUGHT_CONTENT);
    }
}
