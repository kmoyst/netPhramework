<?php

namespace netPhramework\data\mapping;

interface DataItem
{
	public function getField():Field;
	public function getValue():?string;
}