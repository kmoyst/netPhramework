<?php

namespace netPhramework\db\presentation\recordTable\columns;

use netPhramework\common\Utils;
use netPhramework\db\core\Record;
use netPhramework\db\presentation\recordTable\columnSet\Column;
use netPhramework\db\presentation\recordTable\columnSet\ColumnHeader;
use netPhramework\rendering\Encodable;

class TextColumn implements Column
{
	public function __construct(
		protected string $name,
		protected int $width = 150,
		protected ?string $headerText = null) {}

	public function getName(): string
	{
		return $this->name;
	}

	public function getHeader(): ColumnHeader
	{
		$text = $this->headerText ?? Utils::kebabToSpace($this->name);
		return new ColumnHeader($this->name, $text, $this->width);
	}

	public function setWidth(int $width): TextColumn
	{
		$this->width = $width;
		return $this;
	}

	public function getOperableValue(Record $record): string
	{
		return $this->getSortableValue($record);
	}

	public function getSortableValue(Record $record): string
	{
		return $record->getValue($this->name) ?? '';
	}

	/** @inheritDoc */
	public function getEncodableValue(Record $record): Encodable|string
	{
		return $this->getOperableValue($record);
	}
}