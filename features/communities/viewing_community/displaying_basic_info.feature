@viewing_communities
Feature: Basic info about communities
  In order to find users with similar interests
  As a user
  I need to be able to view a community's details

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "PHP Macedonia" and described as "PHP developers from Macedonia"

  Scenario: Displaying basic information about the community
    When I want to view the "PHP Macedonia" community
    Then I should see its description
    And I should see its author
    And I should see its creation date

  Scenario: Displaying basic information about the community to users who hasn't joined the community
    Given there is a community named "Pearl Jam Fans"
    And I haven't joined it
    When I want to view it
    Then I should see its description
    And I should see its author
    And I should see its creation date