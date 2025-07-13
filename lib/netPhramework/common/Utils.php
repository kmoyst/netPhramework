<?php

namespace netPhramework\common;
use DateInterval;
use DateTime;

class Utils
{
	public static function mysqlDateTime(DateTime $dateTime):string
	{
		return $dateTime->format('Y-m-d H:i:s');
	}

	public static function isExpired(
		DateTime $pastTime,
		DateInterval $difference):bool
	{
		return str_starts_with(
			(clone $pastTime)
				->add($difference)
				->diff(new DateTime()),'+');
	}
	public static function kebabToSpace(string $kebab):string
	{
		return ucwords(str_replace('-', ' ', $kebab));
	}

	public static function kebabToCamel(string $kebab):string
	{
		return lcfirst(str_replace('-', '', ucwords($kebab, '-')));
	}

	/**
	 * Also does Pascal
	 *
	 * @param string $camel
	 * @return string
	 */
	public static function camelToKebab(string $camel):string
	{
		return strtolower(preg_replace('|[A-Z]|','-$0',lcfirst($camel)));
	}

	public static function screamingSnakeToSpace(string $SCREAMING_SNAKE):string
	{
		return ucwords(str_replace('_', ' ', strtolower($SCREAMING_SNAKE)));
	}

	public static function baseClassName(string|object $fullClass):string
	{
		$name = is_object($fullClass) ? get_class($fullClass) : $fullClass;
		return preg_replace('|^.*\\\\|', '', $name);
	}
}