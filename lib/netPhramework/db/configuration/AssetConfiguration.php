<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;

abstract class AssetConfiguration
{
	protected RecordMapper $mapper;

	public function setMapper(RecordMapper $mapper):self
	{
		$this->mapper = $mapper;
		return $this;
	}

	abstract public function configurePassiveNode(Directory $root):void;
	abstract public function configureActiveNode(Directory $root):void;

}