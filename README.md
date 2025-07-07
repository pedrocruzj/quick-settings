### About

Quick Settings is a lib to help you quickly retrieve data stored in a key-value structure directly from the database in a Laravel app. It has cache support enabled by default.

### Installation

-   Install package
-   Run php artisan vendor:publish --tag=quick-settings-migrations
-   Run php artisan migrate

### Usage

```
use Petros\QuickSettings\QuickSettings;

$settings = new QuickSettings();

$settings->set("foo", "bar"); // Set value
$settings->get("foo"); // bar is retuned

// Returns null if not exists
$settings->get("keys"); // null is retuned

// Set array
$settings->set("data", ["name" => "Pedro", "age" => 26]);
$settings->get("data") // Returns json {"name":"Pedro","age":26}

// Set object
$obj = new \stdClass;
$obj->name = "Pedro";
$obj->age = 26;
$settings->set("data", $obj);
$settings->get("data") // Returns json {"name":"Pedro","age":26}

// Check if exists
$settings->exists('data') // Returns boolean
```
