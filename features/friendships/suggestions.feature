@managing_friendships
Feature: Getting friends suggestions
  In order to find new friends
  As a user
  I need see a list of suggested friends

  Background:
    Given I am logged in as "john@example.com"

  Scenario: I already have some friends
    Given there are users James, Bill, Brian, Claire, Bob and Jim
    And I am friend with Bill
    And I am friend with Brian
    And I am friend with James
    And Brian is friend with Bill
    And Brian is friend with Jim
    And James is friend with Jim
    And James is friend with Bob
    When I want to manage my friendships
    Then I should see suggestion to add Jim as a friend
    And I should see suggestion to add Bob as a friend

  Scenario: I don't have any friends
    When I want to manage my friendships
    Then I should see a message that there are no friends suggested for me