<?php

namespace netPhramework\db\nodes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\configuration\OneToMany;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordForm\RecordFormBuilder;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategyBasic;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnStrategy;
//use netPhramework\db\presentation\recordTable\FilterContext;
use netPhramework\db\presentation\recordTable\FilterContext;
use netPhramework\db\presentation\recordTable\RecordList;
use netPhramework\db\presentation\recordTable\RecordTableBuilder;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class EditParent extends RecordProcess
{
	public function __construct(
		private readonly OneToMany  $oneToMany,
		private readonly ?RecordFormStrategy $formStrategy = null,
		private readonly ?ColumnStrategy $childColumnStrategy = null,
		private readonly bool $includeFilteringChildTable = false,
		private readonly ?ColumnMapper $columnMapper = null,
		?string $name = 'edit')
	{
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws FieldAbsent
	 * @throws InvalidSession
	 * @throws MappingException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$view = new View('edit-parent')
			->add('editForm',   $this->createEditForm($exchange))
			->add('childTable',	$this->createChildTable($exchange))
		;
		$exchange->display(
			$view->setTitle("Edit Record"),
			$exchange->getSession()->resolveResponseCode())
		;
	}

	private function createEditForm(Exchange $exchange):Viewable
	{
		$callbackInput = $exchange->callbackFormInput();
		$strategy 	   = $this->formStrategy ?? new RecordFormStrategyBasic();
		$inputSet 	   = new RecordFormBuilder($strategy)
			->setRecord($this->record)
			->addRecordInputs()
			->getInputSet()->addCustom($callbackInput)
			;
		return new View('edit-form')
			->add('hasFileInput', $inputSet->hasFileInput())
			->add('inputs', $inputSet)
			->add('action', 'update')
			->add('callbackLink', $exchange->callbackLink())
			;
	}

	/**
	 * @param Exchange $exchange
	 * @return Viewable
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws InvalidSession
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws ValueInaccessible
	 */
	private function createChildTable(Exchange $exchange):Viewable
	{
		$recordSet = $this->oneToMany->getChildren($this->record);
		$compPath  = $exchange->getPath()->pop()->append($recordSet->getName());
		$filterContext = new FilterContext()->parse($exchange->getParameters());
		$builder = new RecordTableBuilder()
			->setRecordSet($recordSet)
			->setCallbackInputForRows($exchange->callbackFormInput(true))
			->setColumnMapper($this->columnMapper)
			->setColumnStrategy($this->childColumnStrategy)
			->setCompositePath($compPath)
			->setFeedback($exchange->getSession()->getEncodableValue())
			->buildColumnSet()
			->buildRowSet()
			;
		if($recordSet->count() > 10) {
			$builder
				->setCallbackInputForFilterForms($exchange->callbackFormInput())
				->setFilterContext($filterContext)
				->applyFilter()
				->buildSelectFilterForm()
				->buildPaginator()
			;
		}
		return $builder
			->buildAddButton()
			->buildRecordList()
			->getRecordTable()
			;
	}
}