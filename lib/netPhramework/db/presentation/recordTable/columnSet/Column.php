<?php

namespace netPhramework\db\presentation\recordTable\columnSet;

use netPhramework\core\Exception;
use netPhramework\db\core\Record;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\rendering\Encodable;

interface Column
{
	public function getName():string;
	public function getHeader():ColumnHeader;

	/**
	 * This is the value that can be operated on and reflects what is seen
	 * by the user
	 *
	 * @param Record $record
	 * @return string
	 * @throws ValueInaccessible
	 * @throws FieldAbsent
	 * @throws Exception
	 */
	public function getOperableValue(Record $record):string;

    /**
	 * This is typically the rawest value - it most closely ressembles what
	 * is stored in the Record.
	 *
     * @param Record $record
     * @return string
     * @throws ValueInaccessible
     * @throws FieldAbsent
	 * @throws Exception
     */
    public function getSortableValue(Record $record):string;

    /**
	 * This is the displayed value. It usually wraps the operable value with
	 * display logic as needed (e.g. adding a $ prefix for currency)
	 *
     * @param Record $record
     * @return Encodable|string
     * @throws ValueInaccessible
     * @throws FieldAbsent
     * @throws Exception
     */
	public function getEncodableValue(Record $record):Encodable|string;
}