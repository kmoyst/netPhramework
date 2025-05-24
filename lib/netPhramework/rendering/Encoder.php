<?php

namespace netPhramework\rendering;

use netPhramework\common\FileFinder;
use netPhramework\dispatching\ReadableLocation;
use netPhramework\dispatching\ReadablePath;
use netPhramework\dispatching\UriFromLocation;
use netPhramework\dispatching\UriFromPath;
use netPhramework\exceptions\FileNotFound;

readonly class Encoder
{
	public function __construct(private FileFinder $templateFinder) {}

	public function encodeText(string $text):string
	{
		return htmlspecialchars($text);
	}

	public function encodeViewable(Viewable $viewable):Buffer|string
    {
		try {
			$path = $this->findTemplatePath($viewable->getTemplateName());
			$variables = $this->encodeIterable($viewable->getVariables());
			return new Buffer($path, $variables);
		} catch (FileNotFound) {
            // @TODO probably log the error here
			return 'template missing';
		}
	}

	/**
	 * @param ReadableLocation $location
	 * @return string
	 */
	public function encodeLocation(ReadableLocation $location):string
	{
		return new UriFromLocation($location);
	}

	/**
	 * @param ReadablePath $path
	 * @return string
	 */
	public function encodePath(ReadablePath $path):string
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
		Encodable|string|iterable|null $encodable):string|iterable
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