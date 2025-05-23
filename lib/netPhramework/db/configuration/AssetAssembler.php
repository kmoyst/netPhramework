<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\core\Exception;
use netPhramework\db\core\Asset;
use netPhramework\db\core\Process;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\core\RecordProcessSet;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\core\RecordSetProcessSet;

class AssetAssembler
{
	protected RecordSetProcessSet $recordSetProcessSet;
	protected RecordProcessSet $recordProcessSet;

	public function __construct(
		protected readonly Directory $directory,
		protected readonly RecordMapper $recordMapper)
	{
		$this->reset();
	}

	protected function reset():void
	{
		$this->recordSetProcessSet 	= new RecordSetProcessSet();
		$this->recordProcessSet		= new RecordProcessSet();
	}

	public function strategy(ProcessStrategy $strategy):self
	{
		$this->process($strategy->createProcess($this->recordMapper));
		return $this;
	}

	public function process(Process $process):self
	{
		if($process instanceof RecordSetProcess)
			$this->recordSetProcessSet->addProcess($process);
		elseif($process instanceof RecordProcess)
			$this->recordProcessSet->addProcess($process);
		return $this;
	}

	/**
	 * @param string $assetName
	 * @return $this
	 * @throws Exception
	 */
	public function commit(string $assetName): self
	{
		$this->directory->composite(new Asset(
			$this->recordMapper->recordsFor($assetName),
			$this->recordSetProcessSet,
			$this->recordProcessSet
		));
		$this->reset();
		return $this;
	}
}