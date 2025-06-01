<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\core\Exception;
use netPhramework\db\exceptions\ColumnAbsent;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\CellSet;
use netPhramework\db\mapping\Record;
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Viewable;

class Row extends Viewable
{
	private CellSet $cellSet;

	public function __construct(
		private readonly ColumnSet $columnSet,
		private readonly Record $record,
		private readonly Input $callbackInput,
		private readonly MutablePath $assetPath
	) {}

	public function getTemplateName(): string
	{
		return 'record-table-row';
	}

	/**
	 * @param string $columnName
	 * @return string
	 * @throws Exception
	 * @throws ColumnAbsent
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 */
	public function getOperationValue(string $columnName):string
	{
		return $this->columnSet
			->getColumn($columnName)
			->getOperationalValue($this->record);
	}

	public function getVariables(): iterable
	{
		// append the id for both
		$this->assetPath->append($this->record->getId());
		return [
			'cellSet' => new RowCellSet($this->columnSet, $this->record),
			'callbackInput' => $this->callbackInput,
			'id' => $this->record->getId(),
			'editPath' =>   (clone $this->assetPath)->append('edit'),
			'deletePath' => (clone $this->assetPath)->append('delete')
		];
	}
}