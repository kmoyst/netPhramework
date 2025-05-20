<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Process;

interface ProcessStrategy
{
	public function createProcess(RecordAccess $access):Process;
}