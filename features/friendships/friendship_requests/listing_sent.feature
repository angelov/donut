@managing_friendships
Feature: Listing sent friendship requests
  In order to see who I wanted to be friends with
  As a user
  I need to be able to see a list of sent friendship requests

  Background:
    Given I am logged in as "john@example.com"

  Scenario: I have sent a friendship request
    Given there is a user Emma
    And I have sent a friendship request to Emma
    But she hasn't responded yet
    When I want to manage my friendships
    Then I should see that I have 1 sent friendship request
    And it should be for Emma

  Scenario: Displaying message when there are no sent friendship requests
    When I want to manage my friendships
    Then I should see that I haven't got any sent friendship requests