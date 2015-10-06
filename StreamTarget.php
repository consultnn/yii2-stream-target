<?php

namespace consultnn\streamTarget;

use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\log\Logger;
use yii\log\Target;

/**
 * Class StreamTarget
 * Implements log target for php stream wrappers (http://php.net/manual/ru/wrappers.php)
 * @package project\vendor\consultnn\streamTarget
 */
class StreamTarget extends Target
{
    public $stream;

    private $_resource = null;

    public function init()
    {
        if (empty($this->stream)) {
            throw new InvalidConfigException("No stream configured.");
        }
        if (($this->_resource = @fopen($this->stream, 'w')) === false) {
            throw new InvalidConfigException("Unable to append to '{$this->stream}'");
        }
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        foreach ($this->messages as $message) {
            var_dump($message);
            var_dump(fwrite($this->_resource, $this->formatMessage($message).PHP_EOL));
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
                $text = Json::encode($text);
            }
        }
        $prefix = $this->getMessagePrefix($message);
        return "{$prefix}[$level][$category] $text";
    }

    /**
     * @inheritdoc
     */
    protected function getContextMessage()
    {
        $context = [];
        foreach ($this->logVars as $name) {
            if (!empty($GLOBALS[$name])) {
                $context[] = "\${$name} = " . Json::encode($GLOBALS[$name]);
            }
        }

        return implode("\t", $context);
    }
}
