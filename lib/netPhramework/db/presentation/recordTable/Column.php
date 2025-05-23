<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\core\Exception;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\rendering\Viewable;

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
    public function getSortableValue(Record $record):string;

    /**
     * @param Record $record
     * @return Viewable|string
     * @throws ValueInaccessible
     * @throws FieldAbsent
     * @throws Exception
     */
	public function getViewableValue(Record $record):Viewable|string;
}