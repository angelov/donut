@browsing_movies
Feature: Listing the movies
  In order to find new movies
  As a user
  I need to be able to see a list of movies

  Background:
    Given I am logged in as "john@example.com"

  Scenario: There are some movies
    Given there is a movie titled "Example Movie"
    And there is a movie titled "Example Movie 2"
    When I want to browse the movies
    Then I should see 2 listed movies
    And those movies should be "Example Movie" and "Example Movie 2"
