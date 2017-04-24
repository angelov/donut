@browsing_movies
Feature: Paginating the shown movies
  In order to easily browse movies
  As a user
  I need to be able to see the movies separated across multiple pages

  Background:
    Given I am logged in as "john@example.com"
    And there are the following genres:
      | Title  |
      | Comedy |
      | Horror |
      | Action |

  Scenario: Hiding pagination when not needed
    Given there are 3 movies
    When I want to browse the movies
    Then I should not see a pagination

  Scenario: There are two pages and the user is on the first
    Given there are 15 movies
    When I want to browse the movies
    And I am on the first page
    Then I should see 12 listed movies
    And I should be able to go to next page
    But I should not be able to go to previous page

  Scenario: There are two pages and the user is on the second
    Given there are 15 movies
    When I want to browse the movies
    And I am on the second page
    Then I should see 3 listed movies
    And I should be able to go to previous page
    But I should not be able to go to next page

  Scenario: Going to second page with selected filters
    Given there are 15 movies from the Comedy genre
    And there are 15 movies from the Action genre
    When I want to browse the movies
    And I choose the Action genre
    And I am on the second page
    Then I should see 3 listed movies
