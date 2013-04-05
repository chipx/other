<?php
/**
 * Class Balancer предназначен для балансировки ключей по серверам
 */
abstract class Balancer {
    /**
     * @var int Длина части ключа, по которой будет выбираться сервер
     */
    private $partLength;
    /**
     * @var array Таблица максимальных значений ключей для каждого сервера
     */
    private $table;
    /**
     * @var number Максимальное значение части ключа
     */
    private $max;
    /**
     * @var array  Конфигурация серверов
     */
    protected $serversConfig;

    /**
     * Инициализация
     * @param int $partLength Длина части ключа
     */
    public function __construct($partLength = 2)
    {
        $this->partLength = $partLength;
        $this->max = pow(10, $partLength) - 1;
    }

    /**
     * Получить длину части ключа
     * @return int
     */
    public function getPartLength()
    {
        return $this->partLength;
    }

    /**
     * Добавить конфигурацию сервера
     * @param $server mixed Конфигурация
     */
    public function addServer($server)
    {
        $this->serversConfig[count($this->serversConfig)+1] = $server;
        $this->prepapreTable();
    }

    /**
     * Получить все конфигурации
     * @return array
     */
    public function getServers()
    {
        return $this->serversConfig;
    }

    /**
     * Выбор сервера по ключу
     * @param $key Ключ
     * @return mixed
     */
    public function getServerBy($key)
    {
        $index = $this->getServerIndex($key);
        return $this->preapreServer($index);
    }

    /**
     * Получить часть ключа
     * @param $key Ключ
     * @return string
     */
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

    /**
     * Получить сервер по индексу
     * @param $key Ключ
     * @return int
     */
    protected function getServerIndex($key)
    {
        $part = (int)$this->getPart($key);
        $index = 1;
        while ($part > $this->table[$index]) {
            $index++;
        }
        return $index;
    }

    /**
     * Подготовка таблицы максимальных значений ключей
     */
    protected function prepapreTable()
    {
        $serverCount = count($this->serversConfig);
        for($i=1; $i < $serverCount; $i++)
        {
            $this->table[$i] = round($this->max/$serverCount * $i);
        }
        $this->table[$serverCount] = $this->max;
    }

    /**
     * Подготовить сервер к использованию
     * @param $index Индекс сервера
     * @return mixed
     */
    abstract protected function preapreServer($index);
}

/**
 * Class DbBalancer предназначен для балансировки ключей по серверам файлам
 */
class FileBalancer extends Balancer {
    /**
     * Подготовить сервер к использованию
     * @param $index Индекс сервера
     * @return mixed
     */
    protected function preapreServer($index)
    {
        return $this->serversConfig[$index];
    }

}

/**
 * Class DbBalancer предназначен для балансировки ключей по серверам БД
 */
class DbBalancer extends Balancer {
    /**
     * @var array Массив подключений
     */
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