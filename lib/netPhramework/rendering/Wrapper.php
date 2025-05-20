<?php

namespace netPhramework\rendering;

class Wrapper implements Viewable, WrapperSetup
{
    private string $title;
    private Viewable $content;
	private string $templateName = 'wrapper';
	private string $titlePrefix = '';

	public function wrap(Wrappable $wrappable):Viewable
    {
        $this->content = $wrappable->getContent();
        $this->title   = $wrappable->getTitle();
        return $this;
    }

	public function setTitlePrefix(string $titlePrefix): self
	{
		$this->titlePrefix = $titlePrefix;
		return $this;
	}

    public function setTitle(string $title): self
    {
        $this->title = $title;
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
        return [
            'content' => $this->content,
            'title' => trim("$this->titlePrefix - $this->title", '- ')
        ];
    }
}