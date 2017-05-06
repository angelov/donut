# SocNet
### A social network without future.

Developed by [Dejan Angelov](http://angelovdejan.me)

**Caution:** This is just an experimental project developed only for educational purposes.
It is not, and never will be, production ready software.

## Requirements

* **PHP** 7.1+
* Some PHP extensions (listed in `composer.json`)
* **MySQL** - Used as primary data storage. (Postgres will probably do the job too, haven't run the tests with it)
* **Redis** - Currently used for some counters (eg. number of thoughts published by user). Will probably use it for 
cache some time later.
* **Neo4j** - Currently used for recommendation engines and mutual friends resolving.
* **RabbitMQ** - Currently using one queue for asynchronous event handling (eg. for sending e-mails)

All the requirements (except PHP) are easily changeable. You just have to implement some interfaces and change
some configuration.

## Installation

* Clone the project
* Run `composer install` (You will be asked for some configuration parameters)
* Create the database with `./bin/console doctrine:database:create`
* Create the schema with `./bin/console doctrine:schema:create` or `./bin/console doctrine:migrations:migrate`
* If you don't want to bother configuring a server, run `./bin/console server:start`
* To run a consumer for the events, run `./bin/console rabbitmq:consume asynchronous_events`

## Tests

To prepare the database for testing, use 
* `./bin/console doctrine:database:create --env=test` and
* `./bin/console doctrine:schema:create --env=test`

### PhpSpec (specs/unit tests)

The PhpSpec specs are placed in the `specs/` directory.

To run them, use `vendor/bin/phpspec run`.

### PHPUnit (integration tests)

I'm using PHPUnit for integration tests. They are placed in the `tests/` directory. 
To run them, use `vendor/bin/phpunit`.

*Note:* The integration tests may be broken at the moment, as I didn't 
pay much attention to them lately :(

### Behat (acceptance tests)

All application's functionalities are described using Gherkin and the feature files
are placed in the `features/` directory. 

The feature contexts are placed in the `src/AppBundle/FeatureContexts` folder. I plan to give them 
some additional love soon and refactor them to use separate Page classes that will represent the application's web pages.

To execute the features using Behat, run `vendor/bin/behat`.

## Notes about directories structure

The application is primarily divided in two subdirectories inside the `src/` directory: `src/AppBundle` 
and `src/SocNet`.

The `AppBundle` contains some dirty parts of the code that are just experimental, doesn't have 
specs or integration tests and probably sit there crying to be refactored.

The `SocNet` is the cleaner part of the code. Everything here has specs or integration tests.

Note that, as the application is not meant to be used in production, there are some edge cases and 
 possible errors that are 
intentionally left unhandled. Some of them are marked as `todos`, some of  them are not.

## Acknowledges

I have learned a lot, especially about Behat, by browing and digging inside the Sylius project.