@viewing_user_profile
Feature: Listing user's friends
  In order to gain information about a user
  As a user
  I need to be able to view a list of their friends

  Background:
    Given I am logged in as "john@example.com"
    And there are users Jamie, Emma and Tom
    And I am friend with Jamie
    And Jamie is friend with Emma

  Scenario: The user has friends
    When I want to view Jamie's profile
    Then I should see that he has 2 friends
    And I should be on the list of friends
    And Emma should also be on the list

#  @todo
#  Scenario: The user has no friends
#    When I want to view Tom's profile
#    Then I should see that he has 0 friends
#    And I should see a message that he has no friends