<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chipx
 * Date: 04.04.13
 * Time: 22:12
 * To change this template use File | Settings | File Templates.
 */
include_once "balancer.php";
class BalancerTest extends PHPUnit_Framework_TestCase {
    public function testCreate()
    {
        $balancer = new FileBalancer();
        $this->assertEquals(2, $balancer->getPartLength());

        $balancer = new FileBalancer(4);
        $this->assertEquals(4, $balancer->getPartLength());
    }

    public function testAddServer()
    {
        $balancer = new FileBalancer(5);
        $balancer->addServer(dirname(__FILE__));
        $balancer->addServer(dirname(__FILE__ . "/../"));

        $servers = $balancer->getServers();
        $this->assertTrue(is_array($servers));
        $this->assertEquals(dirname(__FILE__), $servers[1]);
    }

    public function testGetServerBy()
    {
        $balancer = new FileBalancer(3);
        $servers = array(1 => 'aaa', 2 => 'bbb', 3 => 'ccc');
        $balancer->addServer($servers[1]);
        $balancer->addServer($servers[2]);
        $balancer->addServer($servers[3]);

        $server = $balancer->getServerBy('1');
        $this->assertEquals($servers[1], $server);

        $server = $balancer->getServerBy('45');
        $this->assertEquals($servers[1], $server);

        $server = $balancer->getServerBy('670');
        $this->assertEquals($servers[3], $server);

        $server = $balancer->getServerBy('999');
        $this->assertEquals($servers[3], $server);

        $server = $balancer->getServerBy('10255');
        $this->assertEquals($servers[1], $server);
    }
}
