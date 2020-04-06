# ImageBox
A small image uploading RESTful microservice, based on [Slim Framework](http://www.slimframework.com/).

## Installation
- Set up a new project instance on your server and point webroot to `/path/to/wwwroot/public`;
- Create a database;
- Clone project to project root folder;
- Run `composer install` in project root;
- Copy `.env.example` to `.env` and make required changes;
- Copy `phinx.yml.example` to `phinx.yml` and make required changes;
- Run `php vendor/bin/phinx` in project root;

## Usage
Application has next public API endpoints:

- [Get image](docs/api/image/get.md) : `GET /images/{uuid}`
- [Store image](docs/api/image/store.md) : `POST /images`
