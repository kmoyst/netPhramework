<?php

namespace netPhramework\rendering;
use Stringable;

class Buffer implements Stringable
{
    private string $templatePath;
    private iterable $variables;

    public function __construct(string $templatePath, iterable $variables)
    {
        $this->templatePath = $templatePath;
        $this->variables = $variables;
    }

    public function get():string
    {
        foreach($this->variables as $k => $v) ${$k} = $v;
        unset($k);
        unset($v);
        ob_start();
        include $this->templatePath;
        return ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->get();
    }
}