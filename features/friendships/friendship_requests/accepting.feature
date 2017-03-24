@managing_friendships
Feature: Accepting a received friendship request
  In order to become a friend with somebody
  As a user
  I need to be able to accept their friendship requests

  Background:
    Given I am logged in as "john@example.com"
    And there is a user Emma

  Scenario: Accepting a friendship request
    Given Emma wants us to be friends
    When I want to manage my friendships
    And I accept her friendship request
    Then I should be notified that we've became friends
    And I shouldn't see the request anymore