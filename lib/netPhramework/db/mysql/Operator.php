<?php

namespace netPhramework\db\mysql;
use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\Operator as mappingOperator;

enum Operator:string
{
	case EQUAL = '=';

	/**
	 * @param mappingOperator $o
	 * @return Operator
	 * @throws MysqlException
	 */
	public static function fromMappingOperator(mappingOperator $o):Operator
	{
		return match($o)
		{
			mappingOperator::EQUAL => self::EQUAL,
			default => throw new MysqlException("Invalid Operator")
		};
	}
}