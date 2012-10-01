<?php

class EventableObject extends stdClass
{
	public $num = 1;

	public function increment($event, $by = 1)
	{
		$this->num += $by;
	}
}