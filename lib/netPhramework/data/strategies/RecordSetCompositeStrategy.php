<?php

namespace netPhramework\data\strategies;

use netPhramework\data\core\RecordMapper;
use netPhramework\data\nodes\RecordSetComposite;

interface RecordSetCompositeStrategy
{
	public function create(RecordMapper $mapper):RecordSetComposite;
}