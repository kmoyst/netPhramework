<?php

namespace netPhramework\data\user;

use netPhramework\common\Variables;
use netPhramework\data\core\Record;
use netPhramework\data\core\RecordFinder;
use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\RecordNotFound;
use netPhramework\data\exceptions\RecordRetrievalException;
use netPhramework\exceptions\NotFound;
use netPhramework\user\Session;
use netPhramework\user\UserRole;

readonly class UserManager
{
	public function __construct(
		private RecordFinder $finder,
		public UserRole $defaultRole = UserRole::STANDARD_USER,
		public UserFieldNames $fields = new UserFieldNames()
	) {}

	public function getUser(Record $record):?User
	{
		return new User($record, $this->defaultRole, $this->fields);
	}

	/**
	 * @param string $by
	 * @param string $value
	 * @return User|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordRetrievalException
	 */
	public function findUser(string $by, string $value):?User
	{
		try {
			$record = $this->finder->findUniqueRecord($by, $value);
			return $this->getUser($record);
		} catch (RecordNotFound) {
			return null;
		}
	}

	/**
	 * @param string|Variables|Session $source
	 * @return User|null
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws NotFound
	 * @throws RecordRetrievalException
	 */
	public function findByUsername(string|Variables|Session $source):?User
	{
		if($source instanceof Variables)
		{
			$username = $this->parseForUsername($source);
			if(!$username) throw new NotFound();
		}
		elseif($source instanceof Session)
		{
			$username = $source->user->getUsername();
		}
		else
		{
			$username = $source;
		}
		return $this->findUser($this->fields->username, $username);
	}

	private function parseForUsername(Variables $variables):string|false
	{
		$value = $variables->getOrNull($this->fields->username);
		if($value === null) return false;
		else return $value;
	}
}