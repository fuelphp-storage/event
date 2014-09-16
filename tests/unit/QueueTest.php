<?php

namespace Fuel\Event;

use Codeception\TestCase\Test;

class QueueTest extends Test
{

	/**
	 * @var Queue
	 */
	protected $queue;

	public function _before()
	{
		$this->queue = new Queue();
	}

	public function testPayloads()
	{
		$expected = array(
			array(1),
			array(2),
			array(3),
		);

		$this->queue->queue('event', array(1));
		$this->queue->queue('event', array(2));
		$this->queue->queue('event', array(3));

		$result = $this->queue->getPayload('event');

		$this->assertEquals($expected, $result);
	}

	public function testFlush()
	{
		$expected = array();

		$this->queue->queue('event');

		$result = $this->queue->flush('event');

		$this->assertEquals($expected, $result);
	}

	public function testFlusher()
	{
		$expected = array(array(1));

		$this->queue->queue('event');

		$result = $this->queue->flush('event', function(){
			return 1;
		});

		$this->assertEquals($expected, $result);
	}

	public function testFlusherWithgetPayload()
	{
		$expected = array(array(1));

		$this->queue->queue('event', array(1));

		$result = $this->queue->flush('event', function($event, $number){
			return $number;
		});

		$this->assertEquals($expected, $result);
	}

	public function testFlusherWithMultiplePayloads()
	{
		$expected = array(array(1), array(2));

		$this->queue->queue('event', array(1));
		$this->queue->queue('event', array(2));

		$result = $this->queue->flush('event', function($event, $number){
			return $number;
		});

		$this->assertEquals($expected, $result);
	}

	public function testMultipleFlushesWithGetPayload()
	{
		$expected = array(
			array(2),
		);

		$this->queue->queue('event', array(1));

		$this->queue->flush('event', function($event, $number){
			return $number;
		});

		$result = $this->queue->flush('event', function($event, $number){
			return $number + 1;
		});

		$this->assertEquals($expected, $result);
	}

	public function testMultipleFlushesWithMultiplePayloads()
	{
		$expected = array(
			array(1, 2),
			array(3, 4),
		);

		$this->queue->queue('event', array(1));
		$this->queue->queue('event', array(3));

		$this->queue->on('event', function($event, $number){
			return $number;
		});

		$this->queue->on('event', function($event, $number){
			return $number + 1;
		});

		$result = $this->queue->flush('event');

		$this->assertEquals($expected, $result);
	}

	public function textMultipleFlushesWithPropagationStop()
	{
		$expected = array(
			array(1),
			array(1),
		);

		$this->queue->queue('event');
		$this->queue->queue('event');

		$this->queue->on('event', function($e){
			$e->stopPropagation();
			return 1;
		});

		$this->queue->on('event', function($e){
			return 2;
		});

		$result = $this->queue->flush('event');

		$this->assertEquals($expected, $result);
	}
}
