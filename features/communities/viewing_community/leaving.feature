@viewing_communities
Feature: Viewing a community
  In order to stop being part of the community
  As a user
  I need to be able to leave it

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "PHP Macedonia" and described as "PHP developers from Macedonia"
    And I have joined it

  Scenario: Leaving the viewed community
    When I am viewing the "PHP Macedonia" community
    And I try to leave it
    Then I should be notified that I have left it