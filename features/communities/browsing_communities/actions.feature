@browsing_communities
Feature: Community actions
  In order to do something with the listed communities
  As a user
  I need to be able join them or view them

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "Humans"

  Scenario: I haven't joined the community
    When I am browsing the communities
    Then I should have an option to join the "Humans" community
    But I should not have an option to view it

  Scenario: I have joined the community
    Given I have joined the "Humans" community
    When I am browsing the communities
    Then I should have an option to view the "Humans" community
    But I should not have an option to join it