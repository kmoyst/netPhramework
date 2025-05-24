<?php

namespace netPhramework\rendering;

class Wrapper extends Viewable implements WrapperConfiguration
{
	private Wrappable $wrappable;
	private string $templateName = 'wrapper';
	private string $titlePrefix = '';

	public function wrap(Wrappable $wrappable):Viewable
    {
		$this->wrappable = $wrappable;
        return $this;
    }

	public function setTitlePrefix(string $titlePrefix): self
	{
		$this->titlePrefix = $titlePrefix;
		return $this;
	}

	public function setTemplateName(string $templateName): self
	{
		$this->templateName = $templateName;
		return $this;
	}

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    public function getVariables(): iterable
    {
		$title =
			trim("$this->titlePrefix - ".$this->wrappable->getTitle(),'- ');
		$content = $this->wrappable->getContent();
        return [
            'content' => $content,
            'title' => $title
        ];
    }
}