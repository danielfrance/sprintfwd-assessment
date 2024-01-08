# SprintFWD Assessment

## Introduction:

This application is my submission to the SprintFWD assessment.  This application lets a user keep track of members of different teams and their projects.

The main use case for this application is the API where users are able to CRUD Members, Teams, and Projects.

## Prerequisites

- [Docker](https://www.docker.com/)

## Cloning the Repo

To get started, clone the repository to your local machine:

`git clone https://github.com/danielfrance/sprintfwd-assessment.git`

## Environment Setup

After cloning the project, copy the `.env.example` file to create your own environment settings file:

`cp .env.example .env`

Then, open the `.env` file and set the `BASE_ADMIN_EMAIL` and `BASE_ADMIN_PASSWORD` values.


## Containerization

I've dockerized this application via [Laravel Sail](https://laravel.com/docs/10.x/sail) which is essentially a Laravel opinionated docker container.

In the following commands, I will refer to the shorthand `sail ...` however if you do not have an alias set up yet, you'll need to use the full comman `./vendor/bin/sail ...`

Once you've pulled this repo down, you can should navigate to the directory and follow the steps below.


## Application Setup

- run `composer install`
- run `npm install`
- while in the project directory spin up the application via `sail up`. this will spin up the container. (Make sure you have docker installed)
- run the application migrations by running `sail artisan migrate` in a new terminal window (within the project directory)
- run the application seeders so that you've got some data to work with via `sail artisan db:seed --class=DatabaseSeeder`
- in a separate terminal window, build the front end assets by running `npm run dev`. This will build all the assets via Vite

This should handle spinning up the application for the first time. you can now navigate to `http://localhost:80` in your browser and should see the log-in screen.  Enter the base admin email and password you added to the env file to view the dashboard.

## Application tear down

To safely tear down the application run `sail down`.  You can also run `sail down --rmi all -v` to remove all images and volumes 

## Tests:

I've created several test cases for each Model (Teams, Members, Projects). Run the following commands to execute tests for each model:

prior to running any tests make sure to run `sail artisan config:clear`

- Projects: `sail artisan test --filter ProjectTest`
- Members: `sail artisan test --filter MemberTest`
- Teams: `sail artisan test --filter TeamTest`

To run all tests, use `sail artisan test`.

## API Testing:

I've published the API documentation via Postman.  You can view it at https://documenter.getpostman.com/view/1608731/2s9YsJBXZS

## UI:

I've only created a handful of UI routes. They are:

- /dashboard: a brief overview of the activity within the API
- /projects: an index/list view of all the projects
- /members: an index/list view of all the members
- /teams: and index/list view of all the teams
