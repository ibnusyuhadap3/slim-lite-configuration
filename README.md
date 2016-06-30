# Slim Lite Configuration

Slim Lite Configuration is a file configuration loader for Slim Framework v3.0.0 or above. Just say the file(s), then this package will register the configuration items automatically to Slim settings. This package support with Ini, Php, Json, Xml, Yaml format. If need to change the configuration file, this package can do as you want.

## Requirements

Slim Lite Configuration requires PHP 5.3+ and Slim Framework v3.0.0 or above.

## Installation

The easiest way to installing Slim Lite Configuration is via Composer

```
composer require ibnusyuhada/slim-lite-configuration
```

## Usage

Slim Lite Configuration designed to be simple to use in Slim Framework. You only register the file(s) or directory of config file(s), then configuration immediately ready for use. When you need to change the items of configuration in a file, just say the path file. Internally uses [hassankhan config](https://github.com/hassankhan/config).

### Loading Files

Slim Lite Configuration able to load one file or multi files or optional files at same time. Rather than say the file name one by one, you can the directory of files then configuration ready to use. Initially register Slim Lite Configuration to container

```
// start Slim Framework
$app = new \Slim\App();

// call Slim Container
$container = $app->getContainer();

// register Slim Lite Configuration

// if want to register all files inside directory choose this one
$container["config"] = function ($container){
	return new IS\Slim\Lite\Configuration\Configuration(__DIR__ . "/../config",$container);
};

// if want to register a file choose this one
$container["config"] = function ($container){
	return new IS\Slim\Lite\Configuration\Configuration(__DIR__ . "/../config/config.yaml",$container);
};

// if want to register several files choose this one
$container["config"] = function ($container){
	return new IS\Slim\Lite\Configuration\Configuration([__DIR__ . "/../config/config.yaml", __DIR__ . "/../config/config.php"],$container);
};

// if want to register optional files choose this one
$container["config"] = function ($container){
	return new IS\Slim\Lite\Configuration\Configuration(["config.yaml","?config.php"],$container);
};
```

### Register Per Route

After register Slim Lite Configuration into container, then we can register all items of configuration in specific route.

```
// configuration only available in this route
$app->get("/", function($req,$res,$args){
	var_dump($this->settings);
})->add($container->get("config"));
```

### Register For All Routes

But, if you want all configuration are available for all routes, just do like code below

```
$app = new \Slim\App;
$container = $app->getContainer();
$container["config"] = function ($container){
	return new IS\Slim\Lite\Configuration\Configuration(__DIR__ . "/../config/config.yaml",$container);
};

// register for all routes
$app->add($container->get("config"));

$app->get("/", function($req,$res,$args){
	var_dump($this->settings);
});

$app->get("/blog", function($req,$res,$args){
	var_dump($this->settings);
});

$app->run();
```

### Write Or Update Configuration File

You are allowed to create a file of configuration if the request file is not exist. But you will get update if file configuration is exist. Here the example usage in consep Slim Lite Configuration for all routes:

```
$app->get("/blog", function($req,$res,$args){
	$conf = $this->config;
	
	$array = [
			"coba" => "hasil",
			"first_section" => [
					"three" => 1,
					"two" => 2
			],
			"phpversion" => [
					"one","two"
			],
			"second_section" => [
					"servers" => ["host1","host2","host3"]
			]
	];
	
	$conf->writeConfig($array,dirname(dirname(__FILE__)) . "/config/config.yaml");
	return success;
});
```

### Access All Configuration Items

Let say we have a file config.yaml like below

```
db:
  host: localhost
  user: user
  pass: password
  dbname: exampleapp
```

In design, all items of configuration will be placed in Slim settings and Slim Lite Configuration container. So, when we want to all items we can do with two possible ways in a route

```
$settings = $this->settings;
var_dump($settings);
```

or

```
$conf = $this->config;
var_dump($conf->all());
```

### Access Specific Configuration Item

In fact, when we want to access specific item, it can be done with two possible ways in a route as well

```
$settings = $this->settings;
var_dump($settings["db"]["host"]);
```

or

```
$conf = $this->config;
var_dump($conf->get("db.host"));
```

### Set Configuration Item

Set new value of an item(s), there are two possible ways in a route, first is by using this way

```
$conf = $this->config;
echo $conf->get("db.host"); // output is localhost
$conf->set("db.host","ibnu");
echo $conf->get("db.host"); // output is ibnu
```

In this way, Slim Lite Configuration only update the container but not Slim settings. The second way by update file configuration like explained above.

### Safe Memory Usage

Like explained above, Slim Lite Configuration will save the items of configuration in Slim settings and container. This will need more usage of computer memory. So to safe the memory, we need to remove Slim Lite Configuration from container by placed the code below

```
$container->offsetUnset("config");
```

elsewhere after

```
$container["config"] = function ($container){
	return new IS\Slim\Lite\Configuration\Configuration(__DIR__ . "/../config",$container);
};
```
do this way will not destroy Slim settings.

## Credits
[Ibnu Syuhada](https://github.com/ibnusyuhadap3)

## License
The MIT Licence. See the [License](https://github.com/ibnusyuhadap3/slim-lite-configuration/blob/master/LICENCE.md)
