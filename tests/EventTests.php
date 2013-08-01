<?php

use Fuel\Event\Container;

class EventTests extends PHPUnit_Framework_TestCase
{
	protected $container;

	public function setUp()
	{
		include_once __DIR__.'/../resources/EventableObject.php';

		$this->container = new Container();
	}

	public function testEventClosure()
	{
		$something = 1;

		$this->container->on('my_event', function($e) use(&$something)
		{
			$something++;
		});

		$this->container->trigger('my_event');

		$this->assertEquals($something, 2);
	}

	public function testPreventClosure()
	{
		$something = 1;

		$this->container->on('my_event', function($e){
			$e->stopPropagation();
		});

		$this->container->on('my_event', function($e) use(&$something)
		{
			$something++;
		});

		$this->container->trigger('my_event');

		$this->assertEquals($something, 1);
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testInvalidHandler()
	{
		$this->container->on('my_event', 'not callable');
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testInvalidContext()
	{
		$this->container->on('my_event', function(){}, 'not a context');
	}

	public function testEventArguments()
	{
		$something = 1;

		$this->container->on('my_event', function($e, $amount) use(&$something)
		{
			$something += $amount;
		});

		$this->container->trigger('my_event', 1);

		$this->assertEquals($something, 2);
	}

	public function testReturnValues()
	{
		$expected = array(1, 2, 3);

		$this->container->on('my_event', function(){
			return 1;
		});

		$this->container->on('my_event', function(){
			return 2;
		});

		$this->container->on('my_event', function(){
			return 3;
		});

		$result = $this->container->trigger('my_event');

		$this->assertEquals($expected, $result);
	}

	public function testAllEvent()
	{
		$expected = array(1, 2, 3);

		$this->container->on('all', function(){
			return 1;
		});

		$this->container->on('my_event', function(){
			return 3;
		});

		$this->container->on('all', function(){
			return 2;
		});

		$result = $this->container->trigger('my_event');

		$this->assertEquals($expected, $result);
	}

	public function testUnregisterEventByName()
	{
		$expected = array(2, 3);

		$this->container->on('event', function(){
			return 1;
		});

		$this->container->on('all', function(){
			return 2;
		});

		$this->container->off('event');

		$this->container->on('event', function(){
			return 3;
		});

		$result = $this->container->trigger('event');

		$this->assertEquals($expected, $result);
	}

	public function testArrayCallback()
	{
		$obj = new EventableObject();

		$this->container->on('my_event', array($obj, 'increment'));
		$this->container->trigger('my_event', 2);

		$this->assertEquals($obj->num, 3);
	}

	/**
	 * @requires PHP 5.4
	 */
	public function testEventContext()
	{
		$something = 1;
		$context = new stdClass();
		$context->val = 1;

		$this->container->on('my_event', function() use(&$something)
		{
			$something += $this->val;
		}, $context);

		$this->container->trigger('my_event');

		$this->assertEquals($something, 2);
	}
	/**
	 * @requires PHP 5.4
	 */
	public function testTrait()
	{
		$obj = new EventableObject();
		$something = 1;

		$obj->on('my_event', function() use(&$something){
			$something++;
		});

		$obj->trigger('my_event');

		$this->assertEquals($something, 2);
	}

	/**
	 * @requires PHP 5.4
	 */
	public function testTraitBinding()
	{
		$obj = new EventableObject(true);

		$obj->on('my_event', function() use(&$something){
			$this->num++;
		});

		$obj->trigger('my_event');

		$this->assertEquals($obj->num, 2);
	}

	/**
	 * @requires PHP 5.4
	 */
	public function testTraitBindingOverwrite()
	{
		$obj = new EventableObject(true);
		$obj2 = new EventableObject();

		$obj->on('my_event', function() use(&$something){
			$this->num++;
		}, $obj2);

		$obj->trigger('my_event');

		$this->assertEquals($obj2->num, 2);
		$this->assertEquals($obj->num, 1);
	}

	/**
	 * @requires PHP 5.4
	 */
	public function testTraitPrepend()
	{
		$obj = new EventableObject(true, true);

		$obj->on('my_event', function($e, $o) use($obj){
			$o->increment($e);
		});

		$obj->trigger('my_event');

		$this->assertEquals($obj->num, 2);
	}
}
