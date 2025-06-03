<?php

namespace netPhramework\db\presentation\recordTable\columnSet;

use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\Record;
use netPhramework\rendering\Encodable;

interface Column
{
	public function getName():string;
	public function getHeader():ColumnHeader;

	/**
	 * @param Record $record
	 * @return string
	 * @throws ValueInaccessible
	 * @throws FieldAbsent
	 * @throws Exception
	 */
	public function getOperableValue(Record $record):string;

    /**
     * @param Record $record
     * @return string
     * @throws ValueInaccessible
     * @throws FieldAbsent
	 * @throws Exception
     */
    public function getSortableValue(Record $record):string;

    /**
     * @param Record $record
     * @return Encodable|string
     * @throws ValueInaccessible
     * @throws FieldAbsent
     * @throws Exception
     */
	public function getEncodableValue(Record $record):Encodable|string;
}