<?php

use Fuel\Event\Queue;

class QueueTests extends PHPUnit_Framework_TestCase
{
	protected $queue;

	public function setUp()
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
		
		$result = $this->queue->payload('event');
		
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
	
	public function testFlusherWithPayload()
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
	
	public function testMultipleFlushersWithPayload()
	{
		$expected = array(
			array(1, 2),
		);
		
		$this->queue->queue('event', array(1));
		
		$result = $this->queue->flush('event', function($event, $number){
			return $number;
		});
		
		$result = $this->queue->flush('event', function($event, $number){
			return $number + 1;
		});
		
		$this->assertEquals($expected, $result);
	}
	
	public function testMultipleFlushersWithMultiplePayloads()
	{
		$expected = array(
			array(1, 2),
			array(3, 4),
		);
		
		$this->queue->queue('event', array(1));
		$this->queue->queue('event', array(3));
		
		$result = $this->queue->flush('event', function($event, $number){
			return $number;
		});
		
		$result = $this->queue->flush('event', function($event, $number){
			return $number + 1;
		});
		
		$this->assertEquals($expected, $result);
	}
}