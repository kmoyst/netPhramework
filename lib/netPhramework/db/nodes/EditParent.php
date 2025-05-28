<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exchange;
use netPhramework\db\configuration\OneToMany;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\presentation\recordForm\RecordFormBuilder;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategyBasic;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnSetBuilder;
use netPhramework\db\presentation\recordTable\RowSet;
use netPhramework\rendering\View;

class EditParent extends RecordProcess
{
	public function __construct(
		private readonly OneToMany  $oneToMany,
		?RecordFormStrategy         $formStrategy = null,
		?string                     $name = 'edit')
	{
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$strategy = $this->formStrategy ?? new RecordFormStrategyBasic();
		$inputSet = new RecordFormBuilder($strategy)
			->setRecord($this->record)
			->addRecordInputs()
			->getInputSet()->addCustom($exchange->callbackFormInput())
		;
		$children  = $this->oneToMany->getChildren($this->record);
		$columnSet = new ColumnSetBuilder()
			->setFieldSet($children->getFieldSet())
			->setMapper(new ColumnMapper())
			->getColumnSet()
		;
		$headers = $columnSet->getHeaders()
		;
		$callbackInput   = $exchange->callbackFormInput(true);
		$childAssetPath  = $exchange->getPath()->pop()->append('appointments');
		$rowSet 		 = new RowSet(
			$children, $columnSet, $callbackInput,
			$childAssetPath, $children->getIds())
		;
		$recordTableView = new View('record-table')
			->add('headers', 		$headers)
			->add('rows', 			$rowSet)
		;
		$view = new View('edit-parent')
			->add('childRecordTable', $recordTableView)
			->add('inputs', 		  $inputSet)
			->add('action', 		  'update')
		;
		$exchange->ok($view->setTitle("Edit Record"));
	}
}