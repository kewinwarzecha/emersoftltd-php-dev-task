



## Running the project
1. Create a `.env.local` file from the `.env` file and fill in with the correct data.
2. Build docker containers with the command `docker-compose up -d --build`.
3. Inside the core-php container (you can enter it with the command `docke exec -it core-php bash`):
   - install dependencies with the `composer install` command;
   - Run migrations with command `php bin/console doctrine:migrations:migrate`;
   - generate the SSL keys with command `php bin/console lexik:jwt:generate-keypair`.
4. After configuring the application this way, you will be able to access it at 127.0.0.1:8000.

## Brief

Write a new Symfony application having the following functionality:

- Registering a new user (REST API)
- Authenticating a user (REST API)
- Allowing an authenticated user to change their profile details, like first name, last name an email address (REST API)
- a CLI command saving a CSV file with all the users in the system and saving a reference to the file and a date in the database.

Please include short documentation on how to run the application locally.

We're not trying to catch you out.
Yet we want to see your best. If you feel you can not fully complete the task within a reasonable amount of time, send your best attempt anyway.

## Usage

API documentation can be found at `/api/doc`.

Export of users can be performed with the command `php bin/console users:export`, the file will be saved in the directory `/public/files`.