<?php

namespace netPhramework\data\presentation\recordTable\columns;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\record\Record;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\ImmutableView;

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
		return new ImmutableView('email-address',
			['emailAddress' => $record->getValue($this->name)]);
	}
}