# FuelPHP Event Package

[![Build Status](https://secure.travis-ci.org/fuelphp/event.png)](http://travis-ci.org/fuelphp/event)

Swift and elegant event management in PHP. A simple interface with a lot of power.

## What's included

* Creating event containers for easy management.
* Registering and unregistering of events.
* Event prioritizing.
* Bubbling of events can be prevented.
* A trait for eventable objects.
* Context binding to event handlers.

## A simple example

```php
<?php

use Fuel\Event\Facade as Event;

$container = Event::forge();

$container->on('my_event', function($event){
	// Act on the event
});

$container->trigger('my_event');
``` 

## Unregistering of event

```php
// Unregister all handlers for a specific event
$container->off('my_event');

// Unregister all handlers with a specific handler
$container->off(null, $my_handler);

// Unregister all handlers with a specific context
$conainer->off(null, null, $context);
```

## Adding context

The value of `$this` inside the handler (Closure) can be set by providing an object as the callback context.

```php
$container->on('my_event', function($event){
	// $this is now $myObject
}, $myObject);
```

## Prioritizing events

Events can be priorritized with the addition of a priority number in the `->on` method.

```php
$container->on('my_event', function(){
	// This will be run last
}, 1);

$container->on('my_event', function(){
	// This will be run first
}, 2);
```

## Using contexts and prioritizing together

You can also combine contexts and prioritizing. In this scenario, first define the context and then supply the priority like so:

```php
$container->on('my_event', function(){
	// Do something
}, $context, 3);
```