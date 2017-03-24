@managing_friendships
Feature: Listing my friends
  In order to know who my friends are
  As a user
  I need to be able see a list of them

  Background:
    Given I am logged in as "john@example.com"
    And there are users Angela, Emma and James

  Scenario: I have friends
    Given I am friend with Angela
    And I am friend with James
    When I want to manage my friendships
    Then I should see that I have 2 friends
    And I should see that I am friend with James and Angela

#  @todo
#  Scenario: I don't have friends
#    When I want to manage my friendships
#    Then I should see that I have 0 friends
#    And I should see a message that I still don't have any friends