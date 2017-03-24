@viewing_user_profile
Feature: Listing user's thoughts
  In order to see what a user have said
  As a user
  I need to be able to view their thoughts

  Background:
    Given I am logged in as "john@example.com"
    And there is a user Emma

  Scenario: The user has shared some thoughts
    Given Emma has shared 5 thoughts
    When I want to view Emma's profile
    Then I should see that she has shared 5 thoughts
#    And I should see her thoughts @todo

#  @todo
#  Scenario: The user hasn't shared any thoughts
#    When I want to view her profile
#    Then I should see that she has shared 0 thoughts
#    And I should see a message that she hasn't shared anything yet