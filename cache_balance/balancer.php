<?php
class Balancer {
    private $partLength;
    private $table;
    private $max;
    protected $serversConfig;

    public function __construct($partLength = 2)
    {
        $this->partLength = $partLength;
        $this->max = pow(10, $partLength) - 1;
    }

    public function getPartLength()
    {
        return $this->partLength;
    }

    public function addServer($server)
    {
        $this->serversConfig[count($this->serversConfig)+1] = $server;
        $this->prepapreTable();
    }

    public function getServers()
    {
        return $this->serversConfig;
    }

    public function getServerBy($key)
    {
        $index = $this->getServerIndex($key);
        return $this->preapreServer($index);
    }

    protected function getPart($key)
    {
        $maxLength = strlen((string)$this->max);
        $keyLength = strlen($key);
        $part = "";
        if ($keyLength <= $maxLength)
        {
            $part = $key;
        } else {
            $part = substr($key, $maxLength - strlen($key));
        }
        return $part;
    }

    protected function getServerIndex($key)
    {
        $part = (int)$this->getPart($key);
        $index = 1;
        while ($part > $this->table[$index]) {
            $index++;
        }
        return $index;
    }

    protected function prepapreTable()
    {
        $serverCount = count($this->serversConfig);
        for($i=1; $i < $serverCount; $i++)
        {
            $this->table[$i] = round($this->max/$serverCount * $i);
        }
        $this->table[$serverCount] = $this->max;
    }

    protected function preapreServer($index)
    {
        return $this->serversConfig[$index];
    }
}

class DbBalancer extends Balancer {
    private $servers;
    public function preapreServer($index)
    {
        if (!isset($this->servers[$index])) {
            $config = $this->serversConfig[$index];
            $this->servers[$index] = new PDO($config['dsn'], $config['user'], $config['passwd']);
        }
        return $this->servers[$index];
    }
}