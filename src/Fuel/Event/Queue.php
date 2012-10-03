<?php

namespace Fuel\Event;

class Queue
{
	/**
	 * @var  array  $queue  payloads per queue
	 */
	protected $queue = array();

	/**
	 * @var  array  $container  event container
	 */
	protected $container;

	/**
	 * Appends a payload to the queue
	 */
	public function queue($queue, $payload = array())
	{
		// Check for a queue stack
		if ( ! isset($this->queue[$queue]))
		{
			// Create one if it doesn't exist
			$this->queue = array();
		}

		// Append the payload to the queue
		$this->queue[$queue][] = $payload;
		
		return $this;
	}
	
	public function getQueuePayload($queue)
	{
		return isset($this->queue[$queue]) ? $this->queue[$queue] : array();
	}

	public function addFlusher($queue, $flusher, $context = null, $priority = 0)
	{
		// Ensure there is an event container.
		$this->container or $this->container = new Container();

		// Register the flusher to the event container
		$this->container->on($queue, $flusher, $context, $priority);

		return $this;
	}
	
	public function removeFlusher($queue = null, $flusher = null, $context = null)
	{
		// When there is no event container
		if ( ! $this->container)
		{
			// Skip execution
			return $this;
		}

		// Remove the flusher from the event container.
		$this->container->off($queue, $flusher, $context);
		
		return $this;
	}
	
	public function flush($queue, $flusher = null, $context = null, $priority = 0)
	{
		// Set the return array
		$return = array();

		if ($flusher)
		{
			$this->addFlusher($queue, $flusher, $context, $priority);
		}
		
		// When there is no event container
		if ( ! $this->container)
		{
			// Skip execution
			return $return;
		}
		
		// Get the queue payload
		$queuePayload = $this->getQueuePayload($queue);
		
		foreach ($queuePayload as $payload)
		{
			// Prepend the event
			array_unshift($payload, $queue);

			$return[] = call_user_func_array(array($this->container, 'trigger'), $payload);
		}
		
		return $return;
	}
	
	public function clear($queue)
	{
		if (isset($this->queue[$queue]))
		{
			unset($this->queue[$queue]);
		}
		
		// When there is no event container
		if ( ! $this->container)
		{
			// Skip execution
			return $this;
		}

		// Remove all flushers
		$this->container->off($queue);
		
		return $this;
	}
}