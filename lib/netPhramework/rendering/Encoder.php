<?php

namespace netPhramework\rendering;

use netPhramework\common\FileFinder;
use netPhramework\routing\ReadableLocation;
use netPhramework\routing\Path;
use netPhramework\routing\UriFromLocation;
use netPhramework\routing\UriFromPath;
use netPhramework\exceptions\FileNotFound;
use Stringable;

class Encoder
{
	private FileFinder $templateFinder;

	public function setTemplateFinder(FileFinder $templateFinder): self
	{
		$this->templateFinder = $templateFinder;
		return $this;
	}

	public function encodeText(string $text):string
	{
		return htmlspecialchars($text);
	}

	public function encodeViewable(Viewable $viewable):Stringable|string
    {
		return $this->encodeTemplate(
			$viewable->getTemplateName(), $viewable->getVariables());
	}

	public function encodeTemplate(
		string $templateName, ?iterable $variables = []):Stringable|string
	{
		try {
			$path = $this->findTemplatePath($templateName);
			$variables = $this->encodeIterable($variables);
			return new Buffer($path, $variables);
		} catch (FileNotFound) {
			// @TODO probably log the error here
			return "template missing: $templateName";
		}
	}

	/**
	 * @param ReadableLocation $location
	 * @return string|Stringable
	 */
	public function encodeLocation(ReadableLocation $location):Stringable|string
	{
		return new UriFromLocation($location);
	}

	/**
	 * @param Path $path
	 * @return string|Stringable
	 */
	public function encodePath(Path $path):Stringable|string
	{
		return new UriFromPath($path);
	}

	/**
	 * @param string $templateName
	 * @return string
	 * @throws FileNotFound
	 */
    private function findTemplatePath(string $templateName):string
    {
		return $this->templateFinder->findPath($templateName);
	}

    private function encodeIterable(iterable $iterable):iterable
    {
        $a = [];
        foreach ($iterable as $k => $v) $a[$k] = $this->encode($v);
        return $a;
    }

    private function encode(
		Encodable|string|iterable|null $encodable):Stringable|string|iterable
    {
		if($encodable instanceof Encodable)
			return $encodable->encode($this);
		elseif(is_string($encodable))
            return $this->encodeText($encodable);
        elseif(is_iterable($encodable))
            return $this->encodeIterable($encodable);
        else
            return '';
    }
}