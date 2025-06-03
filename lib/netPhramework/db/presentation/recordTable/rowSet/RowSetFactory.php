<?php

namespace netPhramework\db\presentation\recordTable\rowSet;

use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\presentation\recordTable\query\Query;

class RowSetFactory
{
	private RowRegistry $registry;
	private Collator $collator;

	public function __construct(RowRegistry $registry)
	{
		$this->registry = $registry;
	}

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function initializeCollator(RecordSet $recordSet):self
	{
		$this->collator = new Collator()
			->setRowSet($this->generateRowSet($recordSet->getIds()))
			;
		return $this;
	}

	/**
	 * @param Query $query
	 * @return $this
	 * @throws MappingException
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws RecordNotFound
	 * @throws ValueInaccessible
	 */
	public function collate(Query $query):self
	{
		$this->collator->setQuery($query)
			->select()
			->sort()
			->paginate()
			;
		return $this;
	}

	public function getMappedRowSet():RowSet
	{
		return $this->generateRowSet($this->collator->getMap()->getMapped());
	}

	public function generateRowSet(array $collation):RowSet
	{
		return new RowSet($collation, $this->registry);
	}
}