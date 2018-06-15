@joining_community
Feature: Joining a community
  In order to become part of the community
  As a user
  I need to be able to join it

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "PHP Macedonia" and described as "PHP developers from Macedonia"

  Scenario: The community is open for new members
    Given the community can be joined by anybody
    When I request to join it
    Then I should instantly became a member of the community
    And I should be notified that I have joined it

  Scenario: New members should be approved
    Given new members of the community must be approved
    When I request to join it
    Then I should be notified that my request to join the community is sent
    And I should not be member of the community yet
