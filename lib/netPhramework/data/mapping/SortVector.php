<?php

namespace netPhramework\data\mapping;

class SortVector
{
	private Field $field;
	private SortDirection $direction;

	/**
	 * @param Field $field
	 * @param SortDirection $direction
	 */
	public function __construct(
		Field $field, SortDirection $direction = SortDirection::ASCENDING)
	{
		$this->field 	 = $field;
		$this->direction = $direction;
	}

	public function getField(): Field
	{
		return $this->field;
	}

	public function getDirection(): SortDirection
	{
		return $this->direction;
	}
}