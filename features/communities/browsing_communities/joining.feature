@browsing_communities
Feature: Joining a listed community
  In order to find people with similar interests
  As a user
  I need to be able to join the available communities

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "Humans"

  Scenario: Joining a community
    When I am browsing the communities
    And I try to join the "Humans" community
    Then I should be notified that I have joined the community
