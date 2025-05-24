<?php

namespace netPhramework\db\presentation\recordTable\columns;

use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\ReadonlyView;

class EmailColumn extends TextColumn
{
	public function __construct(string $name,
								int $width = 220,
								?string $headerText = null)
	{
		parent::__construct($name, $width, $headerText);
	}

    /**
     * @param Record $record
     * @return Encodable|string
     * @throws FieldAbsent
     * @throws MappingException
     */
	public function getEncodableValue(Record $record): Encodable|string
	{
		return new ReadonlyView('email-address',
			['emailAddress' => $record->getValue($this->name)]);
	}
}