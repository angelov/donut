@creating_communities
Feature: Creating communities
  In order to communicate with other users with similar interests
  As a user
  I should be able to create new communities

  Background:
    Given I am logged in as "john@example.com"

  Scenario: Creating a community with minimum information
    When I want to create a new community
    And I specify the name as "PHP Macedonia"
    And I try to create it
    Then I should be notified that the community is created

  Scenario: Creating a community with all information
    When I want to create a new community
    And I specify the name as "PHP Macedonia"
    And I specify the description as "PHP developers from Macedonia"
    And I try to create it
    Then I should be notified that the community is created

  Scenario: Trying to create a community without a name
    When I want to create a new community
    And I don't specify the name
    And I try to create it
    Then I should be notified that the name is required