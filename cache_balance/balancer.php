<?php
class Balancer {
    private $elementCount;
    private $serversConfig;

    public function __construct($elementCount = 10)
    {
        $this->elementCount = $elementCount;
    }

    public function getElementCount()
    {
        return $this->elementCount;
    }

    public function addServer($server)
    {
        $this->serversConfig[count($this->serversConfig)+1] = $server;
    }

    public function getServers()
    {
        return $this->serversConfig;
    }

    public function getServerBy($key)
    {
        $index = $this->getServerIndex($key);
        return $this->getServer($index);
    }

    protected function getMax()
    {
        return $this->elementCount * count($this->serversConfig) - 1;
    }

    protected function getPart($key)
    {
        $length = strlen((string)$this->getMax());
        return substr($key, -1, $length);
    }

    protected function getServerIndex($key)
    {
        $part = (int)$this->getPart($key);
        $index = ($part - ($part % $this->getMax()) * $this->getMax()) % $this->elementCount;
        return 2;
    }

    protected function getServer($index)
    {
        return $this->serversConfig[$index];
    }
}