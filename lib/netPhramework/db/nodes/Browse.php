<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSetStrategy;
use netPhramework\db\presentation\recordTable\collation\Query;
use netPhramework\db\presentation\recordTable\ViewBuilder;
use netPhramework\db\presentation\recordTable\ViewStrategy;
use netPhramework\exceptions\InvalidSession;
use netPhramework\presentation\CallbackInput;
use netPhramework\presentation\FeedbackView;

class Browse extends RecordSetProcess
{
	public function __construct(
		protected readonly ?ColumnSetStrategy $columnSetStrategy = null,
		protected readonly ?ViewStrategy $tableViewStrategy = null)
	{
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
		$query  = new Query()->parse($exchange->getParameters());
		$recordTableView = new ViewBuilder()
			->setQuery($query)
			->setRecordSet($this->recordSet)
			->setCompositePath($exchange->getPath()->pop())
			->setCallbackInputForRows(new CallbackInput($exchange))
			->setFeedback(new FeedbackView($exchange->getSession()))
			->buildColumnSet($this->columnSetStrategy)
			->buildRowSetFactory()
			->collate()
			->generateView($this->tableViewStrategy)->setTitle('Browse Records')
			;
		$exchange->display(
			$recordTableView, $exchange->getSession()->resolveResponseCode());
	}
}