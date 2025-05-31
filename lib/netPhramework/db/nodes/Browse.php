<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnStrategy;
use netPhramework\db\presentation\recordTable\FilterContext;
use netPhramework\db\presentation\recordTable\RecordTableBuilder;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\View;

class Browse extends RecordSetProcess
{
	public function __construct(
		private readonly ?ColumnStrategy $columnStrategy = null,
		?string $name = null,
		private readonly ?ColumnMapper   $columnMapper = null)
	{
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
		$recordTable   = new RecordTableBuilder()
			->setCallbackInputForRows($exchange->callbackFormInput())
			->setColumnMapper($this->columnMapper)
			->setColumnStrategy($this->columnStrategy)
			->setCompositePath($exchange->getPath()->pop())
			->setFeedback($exchange->getSession()->getEncodableValue())
			->setFilterContext($filterContext)
			->setRecordSet($this->recordSet)
			->buildColumnSet()
			->buildRowSet()
			->applyFilter()
			->buildAddButton()
			->buildSelectFilterForm()
			->buildPaginator()
			->buildRecordList()
			->getRecordTable()
			;
		$view = new View('browse')
			->setTitle('Browse Records')
			->add('recordTable', $recordTable)
		;
		$exchange->display(
			$view, $exchange->getSession()->resolveResponseCode());
	}
}