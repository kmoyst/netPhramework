<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\FilterContext;
use netPhramework\db\presentation\recordTable\RecordTableBuilder;
use netPhramework\db\presentation\recordTable\RowFactory;
use netPhramework\exceptions\InvalidSession;

class Browse extends RecordSetProcess
{
	protected RecordTableBuilder $recordTableBuilder;

	public function __construct(
		?RecordTableBuilder $recordTableBuilder = null,
		?string $name = null)
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
		$filterContext = new FilterContext()->parse($exchange->getParameters());
		$recordTable = $this->recordTableBuilder
			->setCallbackInputForRows($exchange->callbackFormInput())
			->setCompositePath($exchange->getPath()->pop())
			->setFeedback($exchange->getSession()->getEncodableValue())
			->setRecordSet($this->recordSet)
			->setFilterContext($filterContext)
			->buildColumnSet()
			->buildRowFactory()
			->applyFilter()
			->buildAddButton()
			->buildSelectFilterForm()
			->buildPaginator()
			->buildRecordList()
			->getRecordTable()
			->setTitle('Browse Records')
			;
		;
		$exchange->display(
			$recordTable, $exchange->getSession()->resolveResponseCode());
	}
}