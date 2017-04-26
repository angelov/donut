@managing_friendships
Feature: Ignoring friendship suggestions
  In order to find new friends
  As a user
  I need see an option to ignore existing friendship suggestions

  Background:
    Given I am logged in as "john@example.com"

  Scenario: Ignoring an suggestion
    Given there are users James, Bill, Brian, Claire, Bob and Jim
    And I am friend with Bill
    And I am friend with Brian
    And I am friend with James
    And Brian is friend with Bill
    And Brian is friend with Jim
    And James is friend with Jim
    And James is friend with Bob
    When I want to manage my friendships
    And I choose to ignore the suggestion to add Jim as a friend
    Then I should see suggestion to add Bob as a friend
    But I should not see suggestion to add Jim as a friend
