@browsing_movies
Feature: Filtering the movies by genres and periods
  In order to easily find new movies
  As a user
  I need to be able to filter the shown movies by genres or periods

  Background:
    Given I am logged in as "john@example.com"
    And there are the following genres:
      | Title  |
      | Comedy |
      | Horror |
      | Action |
    And there are the following movies
      | Title                             | Genres                 | Year |
      | Funny movie                       | Comedy                 | 1960 |
      | Funny movie with guns             | Comedy, Action         | 1970 |
      | Movie with fighting               | Action                 | 2005 |
      | Modern movie with guns and clowns | Comedy, Action         | 2017 |
      | Movie that scares me              | Horror                 | 1980 |
      | Scary, but funny movie            | Horror, Comedy         | 1992 |
      | Scary, funny movie with fighting  | Horror, Comedy, Action | 1995 |
      | Unknown movie from the 1990s      | Action                 | 1992 |

  Scenario: Browsing without chosen filters
    When I want to browse the movies
    And I don't specify any filters
    Then I should see 8 listed movies

  Scenario: Browsing movies for specified genre
    When I want to browse the movies
    And I choose the Comedy genre
    Then I should see 5 listed movies
    And those movies should be the following
      | Title                             |
      | Funny movie                       |
      | Funny movie with guns             |
      | Modern movie with guns and clowns |
      | Scary, but funny movie            |
      | Scary, funny movie with fighting  |

  Scenario: Browsing movies from multiple genres
    When I want to browse the movies
    And I choose the Comedy and Action genres
    Then I should see 3 listed movies
    And those movies should be the following
      | Title                             |
      | Funny movie with guns             |
      | Modern movie with guns and clowns |
      | Scary, funny movie with fighting  |

  Scenario: Browsing movies from a period
    When I want to browse the movies
    And I choose the 1990s period
    Then I should see 3 listed movies
    And those movies should be the following
      | Title                             |
      | Scary, but funny movie            |
      | Scary, funny movie with fighting  |
      | Unknown movie from the 1990s      |

  Scenario: Browsing movies from a genre and a period
    When I want to browse the movies
    And I choose the 1990s period
    And I choose the Comedy genre
    Then I should see 2 listed movies
    And those movies should be the following
      | Title                             |
      | Scary, but funny movie            |
      | Scary, funny movie with fighting  |
