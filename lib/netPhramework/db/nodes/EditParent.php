<?php

namespace netPhramework\db\nodes;

use netPhramework\db\presentation\recordTable\columnSet\ColumnSetStrategy;
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
use netPhramework\db\presentation\recordTable\ViewBuilder;
use netPhramework\db\presentation\recordTable\ViewStrategy;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class EditParent extends RecordProcess
{
	public function __construct(
		private readonly OneToMany            $oneToMany,
		private readonly ?RecordFormStrategy  $formStrategy = null,
		protected readonly ?ColumnSetStrategy $childColumnSetStrategy = null,
		protected readonly ?ViewStrategy 	  $childViewStrategy = null,
		private readonly int                  $childFilterThreshold = 5,
		?string                               $name = 'edit')
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
		$recordSet  = $this->oneToMany->getChildren($this->record);
		$compPath   = $exchange->getPath()->pop()->append($recordSet->getName());
		$query 		= new Query()->parse($exchange->getParameters());
		$count		= $recordSet->count();
		return new ViewBuilder()
			->setQuery($query)
			->setRecordSet($recordSet)
			->setCompositePath($compPath)
			->setCallbackInputForRows($exchange->callbackFormInput(true))
			->setCallbackInputForFilterForms($exchange->callbackFormInput())
			->setFeedback($exchange->getSession()->getEncodableValue())
			->buildColumnSet($this->childColumnSetStrategy)
			->buildRowSetFactory()
			->collate()
			->generateView(
				$this->childViewStrategy, $this->childFilterThreshold < $count)
			;
	}
}