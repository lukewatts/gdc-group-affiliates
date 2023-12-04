## About GDC Group Affiliates

The GDC Group Affilates application will read a .txt file called affilate.txt, containing a list of affiliates containing correctly formatted JSON with objects (1 per row) containing the following fields: affiliate_id, name, longitude and latitude. The application will then calculate the distance between the affiliate and the GDC Group office in Dublin (longitude: 53.3340285, latitude: -6.2535495) and return a list of affiliates within 100km ordered by affiliate_id (ascending).

The affilate.txt file is located in the storage/app folder.

## How it works

__IMPORTANT__: The affiliate.txt file is __NOT__ correctly formatted JSON, therefore the application will split the file based on newlines, and not commas.

Therefore, the solution is as follows:


1. The `App\Utilities\Affiliates::toArray` method will loop through each line and parse/decode the JSON object, returning an array of objects with properties (`affiliate_id`, `name`, `latitude` and `longitude`)
3. The `App\Utilities\Affiliates::withinDistance` method will then calculate the distance between the affiliate, using the affiliate's latitude and longitude and calling the `App\Utilities\Haversine::distance` method to get the Great Circle Distance [Great Circle Distance](https://wikipedia.com/wiki/Great-circle_distance) using the GDC Group office in Dublin (longitude: 53.3340285, latitude: -6.2535495) as the origin
4. The application will present the list of affiliates within 100km ordered by affiliate_id (ascending) on the UI

## Affilate.txt file format

The affilate.txt file contains a list of affiliates containing JSON objects (1 per row) containing the following fields:

1. affiliate_id
1. name
1. longitude 
1. latitude

```json
{latitude: "52.986375", user_id: 12, name: "Yosef Giles", longitude: "-6.043701"}
{latitude: "51.92893", user_id: 1, name: "Lance Keith", longitude: "-10.27699"}
...
```

## affiliate.txt file location

The affilate.txt file is located in the storage/affiliates folder.

## Requirements

1. PHP (8.1+)
1. Composer (2.5+)
1. SQLite (3.8.8+)
1. NodeJS (16.15+) + NPM (9.6+)
1. Yarn (1.22+)

## Main Libraries

### Composer

1. Laravel (10.10+)
1. Laravel Breeze (1.26+)
1. Laravel Socialite (5.10+)
1. PHPUnit (10.1+)

### Yarn/NPM

1. Vite (4.0+)
1. AlpineJS (3.4+)
1. TailwindCSS (3.1+)
2. Axios (1.6+)

## Installation

1. Clone the repository
1. CD into the repository from within your terminal
1. Run `composer install`
1. Run `php artisan migrate` (This sets up the users tables to allow for login via Github)
1. Run `php artisan serve`
1. In a separate terminal session, run `yarn install`
1. Run `yarn dev`
1. Visit `http://localhost:8000` (from the `php artisan serve` terminal session, __NOT__ the `yarn dev` session) in your browser, or whichever port the application is running on

## Running List Affiliates

1. Visit `http://localhost:8000` in your browser, or whichever port the application is running on
1. Login using Github
1. Once logged in, click the `List Affiliates` button to view the list of affiliates within 100km ordered by affiliate_id (ascending)

## Tests

### Unit Tests

1. _tests/Unit/HaversineTest_: Unit tests for the `App\Utilities\Haversine` class
2. _tests/Unit/AffiliatesTest_: Unit tests for the `App\Utilities\Affiliates` class

### Mocks

1. _tests/Unit/mocks/affilites.txt_: Mock affiliates.txt file used by `Storage::fake` to generate a consistant data set for the `AffilitesTest` 
