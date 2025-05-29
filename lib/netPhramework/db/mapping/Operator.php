<?php

namespace netPhramework\db\mapping;

use netPhramework\common\EnumToArray;
use netPhramework\db\exceptions\InvalidComparator;

enum Operator:string
{
	use EnumToArray;

	case EQUAL = 'EQUALS';
	case CONTAINS = 'CONTAINS';
	case STARTS_WITH = 'STARTS WITH';
	case GREATER_THAN = 'GREATER THAN';
	case GREATER_OR_EQUAL = "GREATER OR EQUAL";
	case LESS_THAN = 'LESS THAN';
	case LESS_OR_EQUAL = 'LESS OR EQUAL';

	/**
	 * @param string $storedValue
	 * @param string $formValue
	 * @return bool
	 * @throws InvalidComparator
	 */
	public function check(string $storedValue, string $formValue):bool
	{
		if($this === self::CONTAINS)
			return stristr($storedValue, $formValue);
		elseif($this === self::EQUAL)
			return $storedValue == $formValue;
		elseif($this === self::STARTS_WITH)
			return str_starts_with($storedValue, $formValue);
		elseif($this === self::GREATER_THAN)
		{
			if(!is_numeric($formValue) || !is_numeric($storedValue))
				throw new InvalidComparator("Invalid comparator for field");
			return $storedValue > $formValue;
		}
		elseif($this === self::LESS_THAN)
		{
			if(!is_numeric($formValue) || !is_numeric($storedValue))
				throw new InvalidComparator("Invalid comparator for field");
			return $storedValue < $formValue;
		}
		elseif($this === self::GREATER_OR_EQUAL)
		{
			if(!is_numeric($formValue) || !is_numeric($storedValue))
				throw new InvalidComparator("Invalid comparator for field");
			return $storedValue >= $formValue;
		}
		elseif($this === self::LESS_OR_EQUAL)
		{
			if(!is_numeric($formValue) || !is_numeric($storedValue))
				throw new InvalidComparator("Invalid comparator for field");
			return $storedValue <= $formValue;
		}
		else exit(1);
	}
}