<?php
namespace BA\Basys\Webservices\Command;

class AsyncCommandMessage implements AsyncCommandMessageInterface
{
    /**
     * @var mixed
     */
    protected $arguments;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $handler;

    /**
     * @var array
     */
    protected $additional;

    /**
     * @var string
     */
    protected $topicName;

    /**
     * @var int
     */
    protected $attempts = 0;

    /**
     * @var int
     */
    protected $level = 0;

    /**
     * @var int
     */
    protected $delay = 0;

    /**
     * @var int
     */
    protected $priority = AsyncCommandMessageInterface::PRIORITY_LOW;

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority(int $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    public function setDelay(int $delay)
    {
        $this->delay = $delay;

        return $this;
    }

    public function getDelay()
    {
        return $this->delay;
    }

    public function setLevel(int $level)
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getAttempts()
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts)
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function setTopic($topicName)
    {
        $this->topicName = $topicName;

        return $this;
    }

    public function getTopic()
    {
        return $this->topicName;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function setCommand(string $command)
    {
        $this->command = $command;

        return $this;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setHandler(string $className)
    {
        $this->handler = $className;

        return $this;
    }

    public function getAdditional()
    {
        return $this->additional;
    }

    public function setAdditional($data)
    {
        $this->additional = $data;

        return $this;
    }

    public function getHandler()
    {
        return $this->handler;
    }
}
