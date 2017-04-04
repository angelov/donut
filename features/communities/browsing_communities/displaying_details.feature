@browsing_communities
Feature: Details for the communities
  In order to learn more about the listed communities
  As a user
  I need to be able to view some basic information about each of them

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "Humans" and described as "Everyone is welcome"

  Scenario: Community created by me
    When I am browsing the communities
    Then I should see the "Humans" community
    And it should be described as "Everyone is welcome"
    And I should see that it is created by me

  Scenario: Community created by another user
    Given there is a user James
    And he has created the "Book readers" community
    When I am browsing the communities
    Then I should see that the "Book readers" community is created by James
