@managing_friendships
Feature: Declining a received friendship request
  In order to ignore somebody's wish to become friends
  As a user
  I need to be able to decline their friendship requests

  Background:
    Given I am logged in as "john@example.com"
    And there is a user Emma

  Scenario: Declining a friendship request
    Given Emma wants us to be friends
    When I want to manage my friendships
    And I decline her friendship request
    Then I should be notified that the request is removed
    And I shouldn't see the request anymore