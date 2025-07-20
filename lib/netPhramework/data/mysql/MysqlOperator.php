<?php

namespace netPhramework\data\mysql;
use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mapping\Operator;

enum MysqlOperator:string
{
	case EQUAL = '=';

	/**
	 * @param Operator $o
	 * @return MysqlOperator
	 * @throws MysqlException
	 */
	public static function fromMappingOperator(Operator $o):MysqlOperator
	{
		return match($o)
		{
			Operator::EQUAL => self::EQUAL,
			default => throw new MysqlException("Invalid Operator")
		};
	}
}