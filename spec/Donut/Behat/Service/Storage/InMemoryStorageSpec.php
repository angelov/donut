<?php

namespace spec\Angelov\Donut\Behat\Service\Storage;

use Angelov\Donut\Behat\Service\Storage\InMemoryStorage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;

class InMemoryStorageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryStorage::class);
    }

    function it_is_storage()
    {
        $this->shouldImplement(StorageInterface::class);
    }

    function it_sets_values_for_keys()
    {
        $this->set('a', 1);
        $this->set('b', 'c');

        $this->get('a')->shouldReturn(1);
        $this->get('b')->shouldReturn('c');
    }

    function it_overrides_values_for_key()
    {
        $this->set('a', 1);
        $this->set('a', 5);

        $this->get('a')->shouldReturn(5);
    }

    function it_returns_default_value_if_key_is_not_found()
    {
        $defaultValue = 4;

        $this->get('a', $defaultValue)->shouldReturn($defaultValue);
    }

    function it_returns_null_if_key_is_not_found_and_no_default_value_is_specified()
    {
        $this->get('a')->shouldReturn(null);
    }

    function it_is_empty_by_default()
    {
        $this->all()->shouldReturn([]);
    }

    function it_returns_all_stored_elements()
    {
        $this->set('a', 1);
        $this->set('b', 2);

        $all = $this->all();
        $all->shouldHaveCount(2);
        $all->shouldHaveKeyWithValue('a', 1);
        $all->shouldHaveKeyWithValue('b', 2);
    }

    function it_removes_all_stored_elements()
    {
        $this->set('a', 1);
        $this->set('b', 2);

        $this->clear();

        $this->all()->shouldHaveCount(0);
    }
}
