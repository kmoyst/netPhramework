<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\resources\RecordProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\ChildSelector;
use netPhramework\db\presentation\recordForm\{RecordFormBuilder,
	RecordFormStrategy as FormStrategy,
	RecordFormStrategyBasic as BasicFormStrategy};
use netPhramework\db\presentation\recordTable\collation\Query;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSetStrategy;
use netPhramework\db\presentation\recordTable\ViewBuilder;
use netPhramework\db\presentation\recordTable\ViewStrategy;
use netPhramework\exceptions\InvalidSession;
use netPhramework\presentation\CallbackInput;
use netPhramework\presentation\FeedbackView;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class EditParent extends RecordProcess
{
	public function __construct
	(
	private readonly ChildSelector 		$childSelector,
	private readonly FormStrategy 		$formStrategy = new BasicFormStrategy(),
	private readonly ?ColumnSetStrategy $childColumnSetStrategy = null,
	private readonly ?ViewStrategy 		$childViewStrategy = null,
	private readonly int 				$childFilterThreshold = 5
	)
	{}

	public function getName():string { return 'edit'; }

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
		$view   = new View('edit-parent')
			->add('editForm',   $this->editForm($exchange))
			->add('childTable',	$this->childTable($exchange))
		;
		$exchange->display(
			$view->setTitle("Edit Record"),
			$exchange->session->resolveResponseCode())
		;
	}

	/**
	 * @param Exchange $exchange
	 * @return Viewable
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	private function editForm(Exchange $exchange):Viewable
	{
		$callbackInput = new CallbackInput($exchange);
		$inputSet 	   = new RecordFormBuilder($this->formStrategy)
			->setRecord($this->record)
			->addRecordInputs()
			->getInputSet()
			;
		return new View('edit-form')
			->add('hasFileInput', $inputSet->hasFileInput())
			->add('inputs', $inputSet)
			->add('action', 'update')
			->add('callbackInput', $callbackInput)
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
	private function childTable(Exchange $exchange):Viewable
	{
		$assetName	 	  = $this->childSelector->getAssetName();
		$recordSet   	  = $this->childSelector->getChildren($this->record);
		$compPath    	  = $exchange->path->pop()->appendName($assetName);
		$query 		 	  = new Query()->parse($exchange->parameters);
		$includeQueryForm = $recordSet->count() > $this->childFilterThreshold;
		return new ViewBuilder()
			->setQuery($query)
			->setRecordSet($recordSet)
			->setCompositePath($compPath)
			->setCallbackInputForRows(new CallbackInput($exchange, true))
			->setCallbackInputForFilterForms(new CallbackInput($exchange))
			->setFeedback(new FeedbackView($exchange->session))
			->buildColumnSet($this->childColumnSetStrategy)
			->buildRowSetFactory()
			->collate()
			->generateView($this->childViewStrategy, $includeQueryForm)
			;
	}
}