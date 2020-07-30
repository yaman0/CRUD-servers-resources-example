# CRUD-servers-ressources-example
## Summary
Subject: create an application with Symfony to manage two entities linked with an association. Implement an API to manage this ressources.
 
This application manages servers with categories.

## Requirements
- `Php 7.3`
- `composer`
- `docker`
- `node`
- `yarn`

## Installation
- Clone repository
- `composer install`
- `yarn install && yarn encore dev`
- `docker-compose up` 
- `php bin/console doctrine:migrations:migrate`

## Usage
- To manage categories, go to http://localhost:8000/category
- To manage servers, go to http://localhost:8000/server
- To use API, go to http://localhost:8000/api

## Roadmap
 - Complete docker php integration
 - Add unit test for validation and controller
 - Homepage
 - Setup CI for tests
 - Check code coverage
 