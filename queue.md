## Event Queue

An event queue lets you fire events with multiple payloads at a delayed time. Using queue's is a great way to organize events. Where events are only fired with one payload, queue's can hold multiple payloads and listeners, and run through them in one go.

## Setting up a Queue

```php
$queue = new Fuel\Event\Queue();
```

### Adding payloads to the que

```php
$queue->queue('name', array('param 1 - a', 'param 2 - a'));

// Add another one.
$queue->queue('name', array('param 1 - b', 'param 2 - b'));
```

You've now got 2 payloads in the que, ready to get cracking.

### Registering Flushers (Listeners, Workers, ...)

Where in events callbacks are often refered to as Listeners, in Queue they're commonly known as Flushers or Workers. Registering a Flusher works just like adding a Listener to a Container:

```php
$queue->on('name', $callable_flusher, $context, $priority);
```

As you can see in the code above, the queue `on` method can also take a `$priority` param. Like in events emitted from a Container, this will sort the Flushers descending.

```php
$queue->queue('name', array('Hello', 'World'));

$queue->on('name', function($one, $two){
	echo $two.'!';
}, 1);

$queue->on('name', function($one, $two){
	echo $one.' ';
}, 2);
```

When flushed this will echo out `Hello World!`.

## Flushing the queue

In order to run the queue'd payloads by the Flushers you'll have to `flush()`.

```php
$result = $queue->flush('name');
```

When only using one Flusher, you can also define it in the `flush` call.

```php
$result = $queue->flush('name', function(){
	// Flusher logic.
}, $optional_context);
```

## Flush results.

The result of the flushing is a multidimentional array:

```php
result_array(
	payload_one_array(
		flusher_one_payload_one_result(),
		flusher_two_payload_one_result(),
	),
	payload_two_array(
		flusher_one_payload_two_result(),
		flusher_two_payload_two_result(),
	)
)
```