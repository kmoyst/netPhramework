<?php

namespace netPhramework\data\presentation\recordTable\rowSet;

use netPhramework\data\core\Record;
use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\ValueInaccessible;
use netPhramework\data\presentation\recordTable\columnSet\Column;
use netPhramework\exceptions\Exception;
use netPhramework\rendering\Viewable;

class Cell extends Viewable
{
	public function __construct
	(
		private readonly Column $column,
		private readonly Record $record
	)
	{}

	public function getTemplateName(): string
	{
		return 'record-cell';
	}

	/**
	 * @return iterable
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 * @throws Exception
	 */
	public function getVariables(): iterable
	{
		$v = [];
		$v['value'] = $this->column->getEncodableValue($this->record);
		$v['width'] = $this->column->getHeader()->width;
		return $v;
	}
}