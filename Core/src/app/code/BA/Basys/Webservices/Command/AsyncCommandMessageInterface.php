<?php
namespace BA\Basys\Webservices\Command;

interface AsyncCommandMessageInterface
{
    const PRIORITY_NONE = 0;
    
    const PRIORITY_LOW  = 1;

    const PRIORITY_HIGH = 2;

    /**
     * @return string
     */
    public function getArguments();

    /**
     * @param string $arguments
     * @return self
     */
    public function setArguments($arguments);

    /**
     * Return additional data
     *
     * @return string
     */
    public function getAdditional();

    /**
     * Set additional data to be used by the handler
     *
     * @param string $data
     * @return self
     */
    public function setAdditional($data);

    /**
     * Set syncronous command pool name
     *
     * @param string $command
     * @return self
     */
    public function setCommand(string $command);

    /**
     * Get syncronous command pool name
     *
     * @return string
     */
    public function getCommand();

    /**
     * Set RabbitMQ topic name
     * 
     * @param string $topicName 
     * @return self
     */
    public function setTopic($topicName);

    /**
     * Get RabbitMQ topic name
     * 
     * @return string
     */
    public function getTopic();

    /**
     * @return int
     */
    public function getAttempts();

    /**
     * @param int $attempts 
     * @return self 
     */
    public function setAttempts(int $attempts);

    /**
     * @param int $level 
     * @return self
     */
    public function setLevel(int $level);

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $level 
     * @return self
     */
    public function setDelay(int $level);

    /**
     * @return int
     */
    public function getDelay();

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority 
     * @return self 
     */
    public function setPriority(int $priority);

    /**
     * Set handler class name
     *
     * @param string $className
     * @return self
     */
    public function setHandler(string $className);

    /**
     * Get handler class name
     *
     * @return string
     */
    public function getHandler();
}
