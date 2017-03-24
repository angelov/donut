@viewing_user_profile
Feature: Listing mutual friends with user
  In order to see my connection to a user
  As a user
  I need to be able to view our mutual friends

  Background:
    Given I am logged in as "john@example.com"
    And there are users Jamie and Emma
    And I am friend with Jamie
    And Jamie is friend with Emma

  Scenario: We have mutual friends
    When I want to view Emma's profile
    Then I should see that we have 1 mutual friend
    And that friend should be Jamie

#  @todo
#  Scenario: We don't have any mutual friends
#    When I want to view Jamie's profile
#    Then I should see that we have 0 mutual friends
#    And I should see a message that we don't have any mutual friends