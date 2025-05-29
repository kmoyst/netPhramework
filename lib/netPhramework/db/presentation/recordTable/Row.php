<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\mapping\Record;
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Viewable;

class Row extends Viewable
{
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

	public function getVariables(): iterable
	{
		$this->assetPath->append($this->record->getId());
		return [
			'cellSet' => new RecordTableCellSet($this->columnSet,$this->record),
			'callbackInput' => $this->callbackInput,
			'id' => $this->record->getId(),
			'editPath' =>   (clone $this->assetPath)->append('edit'),
			'deletePath' => (clone $this->assetPath)->append('delete')
		];
	}
}