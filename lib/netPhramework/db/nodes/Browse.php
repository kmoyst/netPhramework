<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\query\Query;
use netPhramework\db\presentation\recordTable\Builder as RecordTableBuilder;
use netPhramework\exceptions\InvalidSession;

class Browse extends RecordSetProcess
{
	protected RecordTableBuilder $recordTableBuilder;

	public function __construct(
		?RecordTableBuilder $recordTableBuilder = null,
		?string  $name = null)
	{
		$this->recordTableBuilder =
			$recordTableBuilder ?? new RecordTableBuilder();
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidSession
	 * @throws MappingException
	 * @throws ValueInaccessible
	 * @throws RecordNotFound
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$query = new Query()->parse($exchange->getParameters());
		$recordTable = $this->recordTableBuilder
			->setQuery($query)
			->setRecordSet($this->recordSet)
			->setCompositePath($exchange->getPath()->pop())
			->setCallbackInputForRows($exchange->callbackFormInput())
			->setFeedback($exchange->getSession()->getEncodableValue())
			->buildColumnSet()
			->buildRowRegistry()
			->mapRows()
			->buildSelectFilterForm()
			->buildPaginator()
			->buildAddButton()
			->buildRecordList()
			->getRecordTable()
				->setTitle('Browse Records')
			;
		;
		$exchange->display(
			$recordTable, $exchange->getSession()->resolveResponseCode());
	}
}