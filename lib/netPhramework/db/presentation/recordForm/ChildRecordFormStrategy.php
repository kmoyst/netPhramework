<?php

namespace netPhramework\db\presentation\recordForm;

use netPhramework\db\configuration\OneToMany;
use netPhramework\db\mapping\Cell;
use netPhramework\presentation\FormInput\InputSetBuilder;

readonly class ChildRecordFormStrategy extends RecordFormStrategyBasic
{

	/**
	 * @param string $linkField
	 */
	public function __construct(private readonly string $linkField)
	{
	}


	protected function guessAndAdd(InputSetBuilder $builder, Cell $cell): void
	{
		if($cell->getName() == $this->linkField)
		{
			$builder->hiddenInput(
				$cell->getName())->setValue($cell->getValue());
			return;
		}
		parent::guessAndAdd($builder, $cell);
	}
}