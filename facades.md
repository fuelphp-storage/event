# Facades

The package provides two facades that allow an easy static access to the Event and Queue logic.

## Common Interface

The common interface consists of:

* __forge__: Factory method for the underlying class.
* __instance__: Create and retrieve a globally accessible instance.
* __delete__: Delete one or all globally defined instances contained in the facade.

## Event Facade

Under the hood the Facade provides access to a `Fuel\Event\Container` and maps all the available method to that instance. So now you can do:

```php
<?php

use Fuel\Event\Facade\Event;

// Register an event
Event::on('my_event', function(){}})

// Unregister an event
Event::off('my_event');

// Trigger an event
$result = Event::trigger('my_event');
```

Because the result from the forwarded function is returned you can also chain function:

```php
Event::on('my_event', function(){})
	->on('other_event', function());
```

## Queue

```php
<?php

use Fuel\Event\Facade\Queue;

// Add to the queue
Queue::queue('my_event', $payload);

// Add a listener
Queue::on('my_event', $handler);

// Remove all listener from event my_event
Queue::off('my_event');

// Flush the queue
$result = Queue::flush('my_event');
```

Just like with the Event facade you can chain the calls:

```php
$result = Queue::queue('my_event', $payload)
	->on('my_event', $handler)
	->flush('my_event');
```

## Named instances

Both Queues and Containers have a multiton implementation. This allows you "namespace" queues and events. This is done by using the `::instance` method:

```php

use Fuel\Event\Facade\Event;
use Fuel\Event\Facade\Queue;

// It works for queues
Queue::instance('my_name')->on('event', $handler);

// As well as for events
Event::instance('named')->on('this', $doThat);
```