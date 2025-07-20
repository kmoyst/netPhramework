<?php

namespace netPhramework\data\presentation\recordForm;

use netPhramework\data\mapping\Cell;
use netPhramework\presentation\InputSetBuilder;

readonly class ChildRecordFormStrategy extends RecordFormStrategyBasic
{
	/**
	 * @param string $linkField
	 */
	public function __construct(private string $linkField) {}


	protected function guessAndAdd(InputSetBuilder $builder, Cell $cell): void
	{
		if($cell->getName() == $this->linkField)
		{
			$builder->hiddenInput($cell->getName());
			return;
		}
		parent::guessAndAdd($builder, $cell);
	}
}