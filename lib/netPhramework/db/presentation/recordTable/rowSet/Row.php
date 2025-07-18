<?php

namespace netPhramework\db\presentation\recordTable\rowSet;

use netPhramework\db\core\Record;
use netPhramework\db\exceptions\ColumnAbsent;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\PathException;
use netPhramework\routing\Path;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\Viewable;

class Row extends Viewable
{
	public function __construct(
		private readonly ColumnSet $columnSet,
		private readonly Record $record,
		private readonly Encodable $callbackInput,
		private readonly Path $assetPath
	) {}

	public function getTemplateName(): string
	{
		return 'record-row';
	}

	/**
	 * @param string $columnName
	 * @return string
	 * @throws Exception
	 * @throws ColumnAbsent
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 */
	public function getOperableValue(string $columnName):string
	{
		return $this->columnSet
			->getColumn($columnName)
			->getOperableValue($this->record);
	}

	/**
	 * @param string $columnName
	 * @return string
	 * @throws ColumnAbsent
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 */
	public function getSortableValue(string $columnName):string
	{
		return $this->columnSet
			->getColumn($columnName)
			->getSortableValue($this->record)
			;
	}

	/**
	 * @return iterable
	 * @throws PathException
	 */
	public function getVariables(): iterable
	{
		$this->assetPath->appendName($this->record->getId());
		return [
			'cellSet' => new CellSet($this->columnSet, $this->record),
			'callbackInput' => $this->callbackInput,
			'id' => $this->record->getId(),
			'editPath' => (clone $this->assetPath)->appendName('edit'),
			'deletePath' => (clone $this->assetPath)->appendName('delete')
		];
	}
}