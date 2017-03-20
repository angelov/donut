@viewing_user_profile
Feature: Viewing other users' profiles
  In order to get more information about another user
  As a user
  I need to be able to view their profile

  Background:
    Given I am logged in as "john@example.com"
    And there are users Jamie and Emma
    And I am friend with Jamie
    And Jamie is friend with Emma
    And Emma has shared 5 thoughts

  Scenario: Listing user's friends
    When I want to view Jamie's profile
    Then I should see that he has 2 friends
    And I should be on the list of friends
    And Emma should also be on the list

  Scenario: Listing mutual friends
    When I want to view Emma's profile
    Then I should see that we have 1 mutual friend
    And that friend should be Jamie

  Scenario: Listing user's thoughts
    When I'm viewing Emma's profile
    Then I should see that she has shared 5 thoughts