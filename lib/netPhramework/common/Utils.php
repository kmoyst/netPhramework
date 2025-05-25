<?php

namespace netPhramework\common;
class Utils
{
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
		//return basename(str_replace('\\','/',$fullClassName));
		return preg_replace('|^.*\\\\|', '', $name);
	}
}