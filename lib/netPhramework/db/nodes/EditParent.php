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
use netPhramework\db\presentation\recordTable\query\Query;
use netPhramework\db\presentation\recordTable\views\Builder as RecordTableBuilder;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class EditParent extends RecordProcess
{
	public function __construct(
		private readonly OneToMany           $oneToMany,
		private readonly ?RecordFormStrategy $formStrategy = null,
		private readonly ?RecordTableBuilder $recordTableBuilder = null,
		private readonly int                 $childFilterThreshold = 5,
		?string                              $name = 'edit')
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
	 * @throws RecordNotFound
	 * @throws ValueInaccessible
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
		$query = new Query()->parse($exchange->getParameters());
		$builder = ($this->recordTableBuilder ?? new RecordTableBuilder())
			->setRecordSet($recordSet)
			->setCompositePath($compPath)
			->setCallbackInputForRows($exchange->callbackFormInput(true))
			->setFeedback($exchange->getSession()->getEncodableValue())
			->buildColumnSet()
			->buildRowRegistry()
			;
		if($recordSet->count() > $this->childFilterThreshold) {
			$builder
				->setQuery($query)
				->mapRows()
				->setCallbackInputForFilterForms($exchange->callbackFormInput())
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