<?php

namespace netPhramework\data\resources;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;
use netPhramework\data\exceptions\ValueInaccessible;
use netPhramework\data\nodes\RecordSetProcess;
use netPhramework\data\presentation\recordTable\collation\Query;
use netPhramework\data\presentation\recordTable\columnSet\ColumnSetStrategy;
use netPhramework\data\presentation\recordTable\ViewBuilder;
use netPhramework\data\presentation\recordTable\ViewStrategy;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\presentation\CallbackInput;
use netPhramework\presentation\FeedbackView;

class Browse extends RecordSetProcess
{
	public function __construct
	(
	private readonly ?ColumnSetStrategy $columnSetStrategy = null,
	private readonly ?ViewStrategy 		$tableViewStrategy = null
	)
	{}

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
		$query  = new Query()->parse($exchange->parameters);
		$recordTableView = new ViewBuilder()
			->setQuery($query)
			->setRecordSet($this->recordSet)
			->setCompositePath($exchange->path->pop())
			->setCallbackInputForRows(new CallbackInput($exchange))
			->setFeedback(new FeedbackView($exchange->session))
			->buildColumnSet($this->columnSetStrategy)
			->buildRowSetFactory()
			->collate()
			->generateView($this->tableViewStrategy)->setTitle('Browse Records')
			;
		$exchange->display(
			$recordTableView, $exchange->session->resolveResponseCode());
	}
}