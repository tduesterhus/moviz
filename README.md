# MoviZ - Find an rate your favourit movies!

## Setup

For local development we use [ddev](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/) in combination with docker.
So once ddev and docker are installed and you copied the .env.example file, run `ddev start` in the project root folder and visit https://moviz.ddev.site.

To initialize the application with some sample data run `ddev artisan migrate:fresh --seed`. After that you can log on using `test@example.com` and `password`. 

Once you are done, stop the app by entering `ddev stop` or `ddev poweroff`.

Happy coding!

## External dependencies

We use https://www.omdbapi.com as a source for all the movie data. 
One will need an api key to run the app (you can get one for free on https://www.omdbapi.com).