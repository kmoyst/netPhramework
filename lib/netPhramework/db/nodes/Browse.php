<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnStrategy;
use netPhramework\db\presentation\recordTable\FilterContext;
use netPhramework\db\presentation\recordTable\RecordTable;
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
	 * @throws MappingException
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 * @throws Exception
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$filterContext = new FilterContext()
			->parse($exchange->getParameters())
		;
		$recordTable   = new RecordTable()
			->setAssetPath($exchange->getPath()->pop())
			->setRecordSet($this->recordSet)
			->setCallbackInput($exchange->callbackFormInput(true))
			->setFeedback($exchange->getSession()->getEncodableValue())
			->setColumnMapper($this->columnMapper)
			->setColumnStrategy($this->columnStrategy)
			->applyFilter($filterContext)
			->buildColumnSet()
			->buildRowSet()
			->buildAddButtonView()
			->includeFilterSelector()
			->includePaginator()
		;
		$view = new View('browse')
			->setTitle('Browse Records')
			->add('recordTable', $recordTable)
		;
		$exchange->display(
			$view, $exchange->getSession()->resolveResponseCode());
	}
}