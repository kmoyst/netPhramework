<?php

namespace netPhramework\data\user\profile;

use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\user\User;
use netPhramework\rendering\View;

class ViewManager
{

	public function __construct(public View $view, public User $user) {}

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
	 * @return self
	 * @throws FieldAbsent
	 * @throws MappingException
	 */
	public function add(string $variableName, string $fieldName):self
	{
		$value = $this->user->record->getValue($fieldName);
		$this->view->add($variableName, $value);
		return $this;
	}
}