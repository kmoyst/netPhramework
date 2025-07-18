<?php

namespace netPhramework\db\user\profile;

use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\user\User;
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