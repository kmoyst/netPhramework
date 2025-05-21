<?php

namespace netPhramework\db\mysql;
use netPhramework\db\mapping\Operator as mappingOperator;

enum Operator:string
{
	case EQUAL = '=';

	public static function fromMappingOperator(mappingOperator $o):Operator
	{
		return match($o)
		{
			mappingOperator::EQUAL => self::EQUAL
		};
	}
}