<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\common\Variables;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class RecordTable extends Viewable
{
	private AddButton $addButton;
	private RecordList $recordList;
	private ?View $selectFilterForm;
	private ?View $paginator;
	private ?Encodable $feedback;

	public function setAddButton(AddButton $addButton): self
	{
		$this->addButton = $addButton;
		return $this;
	}

	public function setSelectFilterForm(?View $selectFilterForm): self
	{
		$this->selectFilterForm = $selectFilterForm;
		return $this;
	}

	public function setPaginator(?View $paginator): self
	{
		$this->paginator = $paginator;
		return $this;
	}

	public function setFeedback(?Encodable $feedback): self
	{
		$this->feedback = $feedback;
		return $this;
	}

	public function setRecordList(RecordList $recordList): self
	{
		$this->recordList = $recordList;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'record-table';
	}

	public function getVariables(): iterable
	{
		return new Variables()
			->add('addButton', $this->addButton)
			->add('filterSelector', $this->selectFilterForm ?? '')
			->add('paginator', $this->paginator ?? '')
			->add('feedback', $this->feedback ?? '')
			->add('recordList', $this->recordList)
			;
	}
}