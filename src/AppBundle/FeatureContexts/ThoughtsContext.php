<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use SocNet\Behat\Service\Storage\StorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class ThoughtsContext implements Context
{
    private $session;
    private $router;
    private $storage;

    public function __construct(Session $session, RouterInterface $router, StorageInterface $storage)
    {
        $this->session = $session;
        $this->router = $router;
        $this->storage = $storage;
    }

    /**
     * @When I want to share a thought
     */
    public function iWantToShareAThought() : void
    {
        $url = $this->router->generate('app.thoughts.index');

        $this->session->getDriver()->visit($url);
    }

    /**
     * @When I specify its content as :content
     * @When I don't specify its content
     */
    public function iSpecifyItsContentAs($content = '') : void
    {
        $this->session->getPage()->fillField('Content', $content);

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
        $this->session->getPage()->pressButton('Submit');
    }

    /**
     * @Then I should see it in the list of latest thoughts
     */
    public function iShouldSeeItInTheListOfLatestThoughts() : void
    {
        $page = $this->session->getPage();
        $shared = $this->storage->get('thought_content');

        Assert::true($page->has('css', sprintf('.thought pre:contains("%s")', $shared)));
    }

    /**
     * @Then I shouldn't see it in the list of (latest) thoughts
     */
    public function iShouldnTSeeItInTheListOfThoughts() : void
    {
        $content = $this->storage->get('deleted_thought_content');

        Assert::false($this->session->getPage()->has('css', sprintf('pre:contains("%s")', $content)));
    }

    /**
     * @Then I should be notified that the maximum length is :length characters
     */
    public function iShouldBeNotifiedThatTheMaximumLengthIsCharacters(int $length) : void
    {
        Assert::true($this->session->getPage()->hasContent(sprintf('Thoughts can\'t be longer than %d characters.', $length)));
    }

    /**
     * @Then I should be notified that the thought must have content
     */
    public function iShouldBeNotifiedThatTheThoughtMustHaveContent() : void
    {
        Assert::true($this->session->getPage()->hasContent('Please write the content of your thought.'));
    }

    /**
     * @When I want to browse the thoughts
     * @Given I am browsing the thoughts
     */
    public function iWantToBrowseTheThoughts() : void
    {
        $url = $this->router->generate('app.thoughts.index');

        $this->session->getDriver()->visit($url);
    }

    /**
     * @Then I should see (the rest) :count thoughts from :name
     */
    public function iShouldSeeThoughtsFrom(int $count, string $name) : void
    {
        $thoughts = $this->session->getPage()->findAll('css', sprintf('.thought:contains("by %s")', $name));
        $counted = count($thoughts);

        Assert::same($counted, $count, 'Counted %d instead of %d');
    }

    /**
     * @Then I should see the :count thoughts of mine
     */
    public function iShouldSeeTheThoughtsOfMine(int $count) : void
    {
        $name = $this->storage->get('logged_user')->getName();

        $thoughts = $this->session->getPage()->findAll('css', sprintf('.thought:contains("by %s")', $name));
        $counted = count($thoughts);

        Assert::same($counted, $count, 'Counted %d instead of %d');
    }

    /**
     * @When I delete the :content thought
     */
    public function iDeleteTheThought(string $content) : void
    {
        $thought = $this->session->getPage()->find('css', sprintf('pre:contains("%s")', $content));

        $thought->getParent()->pressButton('delete');

        $this->storage->set('deleted_thought_content', $content);
    }

    /**
     * @Then I should not be allowed to delete the :content thought
     */
    public function iShouldNotBeAllowedToDeleteTheThought(string $content) : void
    {
        $thought = $this->session->getPage()->find('css', sprintf('pre:contains("%s")', $content));
        $parent = $thought->getParent();

        Assert::false($parent->hasButton('delete'), 'Found a forbidden delete button.');
    }

    /**
     * @Then my number of shared thoughts should be :number
     */
    public function myNumberOfSharedThoughtsShouldBe(int $number) : void
    {
        $usersPage = $this->router->generate('app.users.index');
        $this->session->getDriver()->visit($usersPage);

        $name = $this->storage->get('logged_user')->getName();

        // @todo this should be extracted using Page classes
        $card = $this->session->getPage()->find('css', sprintf('#users-list .user-card:contains("%s")', $name));

        // @todo provide a better feedback message
        Assert::true($card->has('css', sprintf('.badge:contains("%d thoughts")', $number)));
    }
}
