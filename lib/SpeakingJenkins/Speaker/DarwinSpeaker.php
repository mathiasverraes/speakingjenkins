<?php
namespace SpeakingJenkins\Speaker;

require_once __DIR__.'/Speaker.php';

class DarwinSpeaker implements Speaker
{
	/**
	 * http://www.gabrielserafini.com/blog/2008/08/19/mac-os-x-voices-for-using-with-the-say-command/
	 */
	private $voice = 'Vicki';

	public function speak($text)
	{
		exec("say -v ".$this->voice." ".escapeshellarg($text));
	}
}