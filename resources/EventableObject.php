<?php

class EventableObject extends stdClass
{
	use Fuel\Event\EventTrait;

	public $num = 1;

	public function __construct($bind = false, $prepend = false)
	{
		$this->_eventPrependSelf = $prepend;
		$this->_eventBindSelf = $bind;
	}

	public function increment($event, $by = 1)
	{
		$this->num += $by;
	}
}
