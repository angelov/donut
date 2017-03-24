@managing_friendships
Feature: Deleting a friendship
  In order to stop being a friend with somebody
  As a user
  I need to be able to delete our friendship

  Background:
    Given I am logged in as "john@example.com"
    And there is a user James
    And we are friends

  Scenario: Deleting existing friendships
    When I want to manage my friendships
    And I want to stop being a friend with James
    Then I should be notified that the friendship is deleted
    And I shouldn't see James in the list of friends anymore
