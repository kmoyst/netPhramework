<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\InputSet;
use netPhramework\rendering\View;

class FilterFormBuilder
{
	private FilterFormStrategy $strategy;
	private FilterFormContext $context;
	private FilterForm $form;

	public function newForm():FilterFormBuilder
	{
		$this->form = new FilterForm();
		return $this;
	}

	public function setStrategy(FilterFormStrategy $strategy): FilterFormBuilder
	{
		$this->strategy = $strategy;
		return $this;
	}

	public function setContext(FilterFormContext $context):FilterFormBuilder
	{
		$this->context = $context;
		return $this;
	}

	public function setFormName():FilterFormBuilder
	{
		$this->form->setFormName($this->strategy->getFormName());
		return $this;
	}

	public function setButtonText():FilterFormBuilder
	{
		$this->form->setButtonText($this->strategy->getButtonText());
		return $this;
	}

	public function setTemplateName():FilterFormBuilder
	{
		$this->form->setTemplateName($this->strategy->getTemplateName());
		return $this;
	}

	public function addLimitInput():FilterFormBuilder
	{
		$key = FilterKeys::LIMIT->value;
		$limit = $this->context->getLimit();
		$input = $this->strategy->createLimitInput($key, $limit);
		$this->form->setLimitInput($input);
		return $this;
	}

	public function addOffsetInput():FilterFormBuilder
	{
		$key = FilterKeys::OFFSET->value;
		$offset = $this->context->getOffset();
		$input = $this->strategy->createOffsetInput($key, $offset);
		$this->form->setOffsetInput($input);
		return $this;
	}

	public function addSortInputs():FilterFormBuilder
	{
		$inputSet = new InputSet();

	}
/**
	public function addSortFieldInput():FilterFormBuilder
	{
		$field = $this->context->getSortField();
		$key = FilterKeys::SORT_FIELD->value;
		$input = $this->strategy->createSortFieldInput($key, $field);
		$this->form->setSortFieldInput($input);
		return $this;
	}

	public function addSortDirectionInput():FilterFormBuilder
	{
		$k = FilterKeys::SORT_DIRECTION->value;
		$d = $this->context->getSortDirection();
		$i = $this->strategy->createSortDirectionInput($k, $d);
		$this->form->setSortDirectionInput($i);
		return $this;
	}
**/
	public function getFilterForm(): FilterForm
	{
		return $this->form;
	}
}