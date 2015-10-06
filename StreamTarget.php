<?php


namespace project\vendor\consultnn\streamTarget;

use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\log\Target;

/**
 * Class StremTarget
 * Implements log target for php stream wrappers (http://php.net/manual/ru/wrappers.php)
 * @package project\vendor\consultnn\streamTarget
 */
class StreamTarget extends Target
{
    public $stream;

    public function init()
    {
        if (empty($this->stream)) {
            throw new InvalidConfigException("No stream configured.");
        }
        if (($fp = @fopen($this->stream, 'w')) === false) {
            throw new InvalidConfigException("Unable to append to '{$this->stream}'");
        }
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        foreach ($this->messages as $message) {
            fwrite($this->stream, $this->formatMessage($message));
        }
    }

    /**
     * @inheritdoc
     */
    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof \Exception) {
                $text = (string) $text;
            } else {
                $text = VarDumper::export($text);
            }
        }
        $prefix = $this->getMessagePrefix($message);
        return "{$prefix}[$level][$category] $text";
    }
}
