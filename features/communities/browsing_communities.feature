@browsing_communities
Feature: Browsing communities
  In order to find people with similar interests
  As a user
  I need to be able to browse the available communities

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "Humans" and described as "Everyone is welcome"
    And there is a community named "Software Engineers"

  Scenario: Displaying all existing communities
    When I want to browse the communities
    Then I should see 2 listed communities

  Scenario: Having an option to join a community
    When I am browsing the communities
    Then I should have an option to join the "Humans" community

  Scenario: Joining a community
    When I am browsing the communities
    And I try to join the "Humans" community
    Then I should be notified that I have joined the community

  Scenario: Having an option to view a community
    Given I have joined the "Humans" community
    When I am browsing the communities
    Then I should have an option to view the "Humans" community
