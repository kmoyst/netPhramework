<?php

namespace netPhramework\db\mapping;

interface DataItem
{
	public function getField():Field;
	public function getValue():?string;
}