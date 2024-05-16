# food label maker task

### we need to run only one command will setup all and also will start containers`./setup.sh`
- If you encounter issues with execution permissions, you can resolve them by running the following commands: `chmod +x setup.sh` to be executable then run `./setup.sh`
### postman collection with some examples of responses **[link](https://documenter.getpostman.com/view/2494634/2sA3JRYyV1)** 
- After calling the login API, the authentication token will be stored locally within the application with collection scope. This token will then be automatically injected into subsequent API requests for authentication purposes.

### Run Tests `./vendor/bin/sail test`
### Start Sail containers if not running yet `./vendor/bin/sail up -d` to stop all containers run `./vendor/bin/sail down`

### If we need to prepare app step by step
- setup composer dependency by only docker in you environment `docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs`
       
- cope environment  `cp .env.example .env`
- Generate JWT secret `./vendor/bin/sail artisan jwt:secret`
- Generate application key `./vendor/bin/sail artisan key:generate --ansi`
- Start Sail containers `./vendor/bin/sail up -d`
- Migrate and seed the database `./vendor/bin/sail artisan migrate --seed`
- Run Tests `./vendor/bin/sail test`



