# FuelPHP Event Package

[![Build Status](https://secure.travis-ci.org/fuelphp/event.png)](http://travis-ci.org/fuelphp/event)

Swift and elegant event management in PHP. A simple interface with a lot of power.

## What's included

* Creating event containers for easy management.
* Registering and unregistering of events.
* Event prioritizing.
* Propagation of events can be prevented.
* A trait for eventable objects.
* Context binding to event handlers.
* Queues ([with docs](https://github.com/fuelphp/event/blob/master/queue.md)).
* Easy access through handy Facades ([docs](https://github.com/fuelphp/event/blob/master/facades.md))

## A simple example

```php
<?php

$container = new FuelPHP\Event\Container();

$container->on('my_event', function($event){
	// Act on the event
});

$container->trigger('my_event');
```

### The special 'all' event

The only reserved event for `Container`s and `Queue`s is the `'all'` event. Listeners for this event
will be triggered on every event fired:

```php
$container->on('all', function(){
	echo 'This will also be fired!';
});

$container->trigger('event');
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

Events can be prioritized with the addition of a priority number in the `->on` method.

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

__Context binding for Closure handlers is only available in PHP >= 5.4.0__

## Triggering events

You can trigger an event like so:

```php
$container->trigger('my_event');
```

In some cases you'll want to pass arguments to the callback, every argument after the event name will be passed to the handler. Those arguments will be appended to the arguments array used to fire the handler. The first argument is always the event object. Following are the params you've profided in `->trigger()`.

```php
$container->on('my_event', function($event, $param1, $param2){
	// do stuff with $param1 and $param2
});

// Trigger the event with params.
$container->trigger('my_event', 'param 1', 'param 2');
```

## Prevent event propagation

You can break the chain of event listeners by calling `stopPropagation` on the event object.

```php
$container->on('my_event', function($e){
	$event->stopPropagation();
});

$container->on('my_event', function($e){
	// This will not get executed.
});

$container->trigger('my_event');
```

## Getting results

When an event is triggered, all the return values will be collected and returned.

```php
$container->on('my_event', function(){
	return 1;
});

$container->on('my_event', function(){
	return 2;
});

$container->on('my_event', function(){
	return 3;
});

$result = $container->trigger('my_event');
// [1, 2, 3]
```

## Eventable objects

PHP 5.4 gives us `traits`, an awesome way to share functionalities and allow for multiple inheritance. Models can become eventable when they use the `FuelPHP\Event\Eventable` trait. Using it is pretty straight forward.

### Implementing the trait

```php
<?php

class EventableObject
{
	// Incluse/use the trait
	use \FuelPHP\Event\Behaviour\Eventable;
}

// Get a new instance.
$myObject = new EventableObject();
```

Now your models/object instances have the power of events under their hood. So the following becomes possible:

```php
$myObject = new EventableObject();

$myObject->on('event', function($event){
	// act on the event
});
```

### Configuration options

There are 2 configuration options to make it even easier to work with eventable objects, which can:

* make objects self binding,
* auto prepend itself to the arguments array.

### Self binding objects

```php
<?php

class EventableObject
{
	use FuelPHP\Event\Behaviour\Eventable;

	// Set to true to bind itself as the callback context.
	protected $_eventBindSelf = true;
}

$myObject = new EventableObject();

$myObject->on('event', function(){
	// $this is now $myObject
});
```

You are still able to overwrite the context by supplying it.

```php
$myObject->on('event', function(){
	// $this is now $otherObject
}, $otherObject);
```

### Self prepending object

Use this when you want to prepend the model to the arguments array.

```php
<?php

class EventableObject
{
	use FuelPHP\Event\Behaviour\Eventable;

	// Set to true to prepend itself to the arguments array.
	protected $_eventPrependSelf = true;
}

$object = new EventableObject();

$object->on('event', function($event, $self){
	// $self now is $object
});
```

When supplying params to the `->trigger` method they will be appended after the event and model:

```php
$object->on('event', function($event, $self, $param1, $param2){
	// Act on the event.
});

$object->trigger('event', 'param 1', 'param 2');
```

## Enjoy!

### For any questions about this package, hop into the #fuelphp IRC channel on freenode.net and look for FrenkyNet.