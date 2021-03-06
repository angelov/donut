<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Angelov\Donut\Behat\Pages\Thoughts\HomePage;
use Angelov\Donut\Behat\Pages\Users\BrowsingUsersPage;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsCheckerInterface;
use Webmozart\Assert\Assert;

class ThoughtsContext implements Context
{
    private $storage;
    private $homePage;
    private $validationErrorsChecker;
    private $browsingUsersPage;

    public function __construct(
        HomePage $homePage,
        BrowsingUsersPage $browsingUsersPage,
        StorageInterface $storage,
        ValidationErrorsCheckerInterface $validationErrorsChecker
    ) {
        $this->storage = $storage;
        $this->homePage = $homePage;
        $this->validationErrorsChecker = $validationErrorsChecker;
        $this->browsingUsersPage = $browsingUsersPage;
    }

    /**
     * @When I want to share a thought
     * @When I want to browse the thoughts
     * @Given I am browsing the thoughts
     */
    public function iWantToShareAThought() : void
    {
        $this->homePage->open();
    }

    /**
     * @When I specify its content as :content
     * @When I don't specify its content
     */
    public function iSpecifyItsContentAs($content = '') : void
    {
        $this->homePage->specifyThoughtContent($content);

        $this->storage->set('thought_content', $content);
    }

    /**
     * @Given I specify its content as something longer than :length characters
     */
    public function iSpecifyItsContentAsSomethingLongerThanCharacters(int $length) : void
    {
        // @todo extract string generating
        $list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ<>?:"{}!@#$%^&';
        $list = str_shuffle($list);

        if (strlen($list) < $length+1) {
            $list .= $list;
        }

        $content = substr($list, 0, $length+1);

        $this->iSpecifyItsContentAs($content);
    }

    /**
     * @When I try to share it
     */
    public function iTryToShareIt() : void
    {
        $this->homePage->shareThought();
    }

    /**
     * @Then I should see it in the list of latest thoughts
     */
    public function iShouldSeeItInTheListOfLatestThoughts() : void
    {
        $shared = $this->storage->get('thought_content');

        Assert::true(
            $this->homePage->containsThought($shared)
        );
    }

    /**
     * @Then I shouldn't see it in the list of (latest) thoughts
     */
    public function iShouldnTSeeItInTheListOfThoughts() : void
    {
        $content = $this->storage->get('deleted_thought_content');

        Assert::false(
            $this->homePage->containsThought($content)
        );
    }

    /**
     * @Then I should be notified that the maximum length is :length characters
     */
    public function iShouldBeNotifiedThatTheMaximumLengthIsCharacters(int $length) : void
    {
        $message = sprintf('Thoughts can\'t be longer than %d characters.', $length);

        Assert::true(
            $this->validationErrorsChecker->checkMessageForField('Content', $message)
        );
    }

    /**
     * @Then I should be notified that the thought must have content
     */
    public function iShouldBeNotifiedThatTheThoughtMustHaveContent() : void
    {
        Assert::true(
            $this->validationErrorsChecker->checkMessageForField('Content', 'Please write the content of your thought.')
        );
    }

    /**
     * @Then I should see (the rest) :count thoughts from :name
     */
    public function iShouldSeeThoughtsFrom(int $count, string $name) : void
    {
        $counted = $this->homePage->countThoughtsFromAuthor($name);

        Assert::same($counted, $count, 'Counted %d instead of %d');
    }

    /**
     * @Then I should see the :count thoughts of mine
     */
    public function iShouldSeeTheThoughtsOfMine(int $count) : void
    {
        $name = $this->storage->get('logged_user')->getName();
        $counted = $this->homePage->countThoughtsFromAuthor($name);

        Assert::same($counted, $count, 'Counted %d instead of %d');
    }

    /**
     * @When I delete the :content thought
     */
    public function iDeleteTheThought(string $content) : void
    {
        $this->homePage->deleteThought($content);

        $this->storage->set('deleted_thought_content', $content);
    }

    /**
     * @Then I should not be allowed to delete the :content thought
     */
    public function iShouldNotBeAllowedToDeleteTheThought(string $content) : void
    {
        try {
            $this->homePage->deleteThought($content);

            throw new \Exception('Found a forbidden delete button.');
        } catch (\Exception $e) {
            // it's alright
        }
    }

    /**
     * @Then my number of shared thoughts should be :number
     */
    public function myNumberOfSharedThoughtsShouldBe(int $number) : void
    {
        $this->browsingUsersPage->open();
        $name = $this->storage->get('logged_user')->getName();
        $card = $this->browsingUsersPage->getUserCard($name);

        Assert::eq($number, $card->getNumberOfThoughts(), 'Expected number of thoughts to be %s, but it is %s.');
    }
}
