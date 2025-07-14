<?php

namespace netPhramework\db\nodes;

interface AssetResourceSet
{
	public RecordChildSet $recordChildSet {get;}
	public AssetChildSet $assetChildSet {get;}
}