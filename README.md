# SprintFWD Assessment
I've dockerized this application via [Laravel Sail](https://laravel.com/docs/10.x/sail) which is essentially a Laravel opinionated docker container.

In the following commands, I will refer to the shorthand `sail ...` however if you do not have an alias set up yet, you'll need to use the full comman `./vendor/bin/sail ...`

Once you've pulled this repo down, you can should navigate to the directory and follow the steps below:


## Setup
- run `composer install`
- run `npm install`
- create an `.env` file and add:
    - a `BASE_ADMIN_EMAIL` with a value
    - a `BASE_ADMIN_PASSWORD` with a value
- spin up the application via `sail up` - this will spin up the container. (Make sure you have docker installed)
- run the application migrations by running `sail artisan migrate` in a new terminal window (within the project directory)
- run the application seeders so that you've got some data to work with via `sail artisan db:seed --class=DatabaseSeeder`
- in a separate terminal window, build the front end assets by running `npm run dev`. This will build all the assets via Vite

This should handle spinning up the application for the first time. you can now navigate to `http://localhost` in your browser and should see the log-in screen.  Enter the base admin email and password you added to the env file to view the dashboard.

## Tests:

I've created several test cases for each Model (Teams, Members, Projects).  You can run them running the following command:

- `sail artisan test --filter ProjectTest` || `sail artisan test --filter MemberTest` || `sail artisan test --filter TeamTest`

## API Testing:

I've published the API documentation via Postman.  You can view it at https://documenter.getpostman.com/view/1608731/2s9YsJBXZS

## UI:

I've only created a handful of UI routes. They are:

- /dashboard: a brief overview of the activity within the API
- /projects: an index/list view of all the projects
- /members: an index/list view of all the members
- /teams: and index/list view of all the teams
