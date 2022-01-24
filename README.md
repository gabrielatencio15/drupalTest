# Weather API Modules


## Features
-Customed Weather query module.
-New Country Manager module.
-New City Manager module.
-New API Manager module.
-Some features fixed.
-Some styling fixs.
-Security important things.

## Tech

dwddw

## Installation

Weather API requires [Drupal 9](https://www.drupal.org/about/9) + to run.

Install the dependencies and devDependencies and start the server. You'll need php and composer, and then can run these two commands:

```sh
composer create-project drupal/weatherapi drupal  && cd drupal
php -d memory_limit=256M web/core/scripts/drupal quick-start demo_umami
```

-Devel library is  recommended for this project to clear the cache after installing.
```sh
composer require drupal/devel
```

## Modules


To install the modules, clone the repository or download the package and copy the following folders inside your_drupal_project/modules:

- weather-api
- wa_country_manager
- wa_city_manager
- wa_api_manager

Then, go to the administration panel and enable the each of one of the modules located into the "wheather" group. Assign each module to the menu as you may desire. You may want to go directly to your new modules following these addresses to start including values to the database, please, follow the order shown below:

- Weather Country Manager: YOUR_HOST/your_drupal_project`/manageCountries`
- Weather City Manager: YOUR_HOST/your_drupal_project`/manageCities`
- Weather API Manager: YOUR_HOST/your_drupal_project`/manageApis`

-You could add an initial country, values such as: Colombia, co.
-You could add an initial city, values such as: Bogota, bogota, 1. Where the last value (1) would be the first added country, to fill the last input, you need to know the value ID of the counrty which the city you want to add belongs to.

-Now you have a country and a couple cities added, you need to go to the Weather API Manager to enther the needed values for the API to work. So let's doit, add the following parameters and you will be happily set to start using this amazing Weather API, add these parameters:

- param: `endpoint` and value: `https://api.openweathermap.org/data/2.5/weather`
- param: `API_ID` and value: for security reasons, please contact me at: gabriel_atencio@hotmail.com to request a valid API token.
- param: `weather_unit` and value: `metric` (You can also use: `imperial`)

# AND THAT'S IT!

You are all set, now you could clean the cache by using the Devel stuff for it, to visit the Weather API module you do not need to sign in nor register, just go to this module (YOUR_HOST/your_drupal_project`/weather-api` and enjoy :)


## License

Gabriel Atencio
Software Developer
[My LinkedIn Profile](https://linkedin.com/in/gabriel-atencio)
(C) Copyright 2022

**Free Software, Hell Yeah!**