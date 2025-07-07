<?php

namespace netPhramework\db\authentication\profile\presentation;

use netPhramework\db\authentication\User;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

readonly class ViewManager
{
	public View $view;

	public function __construct(
		public User $user,
		string $templateName = 'view-profile'
	)
	{
		$this->view = new View($templateName);
	}

	/**
	 * @param string $variableName
	 * @param string $fieldName
	 * @return $this
	 * @throws MappingException
	 */
	public function optionalAdd(string $variableName, string $fieldName):self
	{
		try {
			$this->add($variableName, $fieldName);
		} catch (FieldAbsent)
		{
			$this->view->add($variableName, '');
		}
		return $this;
	}

	/**
	 * @param string $variableName
	 * @param string $fieldName
	 * @return $this
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function mandatoryAdd(string $variableName, string $fieldName):self
	{
		$this->add($variableName, $fieldName);
		return $this;
	}

	public function addCustom(
		string $variableName, string|Encodable|null $value):self
	{
		$this->view->add($variableName, $value);
		return $this;
	}

	/**
	 * @param string $variableName
	 * @param string $fieldName
	 * @return void
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	private function add(string $variableName, string $fieldName):void
	{
		$this->view->add($variableName, $this->user->getValue($fieldName));
	}
}