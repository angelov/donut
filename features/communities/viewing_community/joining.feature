@viewing_communities
Feature: Joining a community
  In order to become part of the community
  As a user
  I need to be able to join it

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "Pearl Jam Fans"
    And I haven't joined it

  Scenario: Joining the viewed community
    When I am viewing the "Pearl Jam Fans" community
    And I try to join it
    Then I should be notified that I have joined it