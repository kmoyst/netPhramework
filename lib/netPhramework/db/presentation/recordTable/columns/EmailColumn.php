<?php

namespace netPhramework\db\presentation\recordTable\columns;

use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\rendering\ReadableView;
use netPhramework\rendering\Viewable;

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
     * @return Viewable|string
     * @throws FieldAbsent
     * @throws MappingException
     */
	public function getViewableValue(Record $record): Viewable|string
	{
		return new ReadableView('email-address',
			['emailAddress' => $record->getValue($this->name)]);
	}
}