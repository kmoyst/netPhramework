<?php

namespace netPhramework\db\nodes;

abstract class RecordSetProcess extends AssetResource implements RecordSetChild
{
	use HasRecordSet;

	public function enlist(AssetResourceDepot $depot):void
	{
		$depot->recordSetChildSet->add($this);
	}
}