<?php

namespace netPhramework\data\presentation\recordTable\rowSet;

use netPhramework\data\core\Record;
use netPhramework\data\exceptions\ColumnAbsent;
use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\ValueInaccessible;
use netPhramework\data\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\PathException;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\EncodableViewable;
use netPhramework\routing\Path;

class Row extends EncodableViewable
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