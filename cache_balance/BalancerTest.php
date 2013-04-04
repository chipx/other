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
        $balancer = new Balancer();
        $this->assertEquals(10, $balancer->getElementCount());

        $balancer = new Balancer(50);
        $this->assertEquals(50, $balancer->getElementCount());
    }

    public function testAddServer()
    {
        $balancer = new Balancer(5);
        $balancer->addServer(dirname(__FILE__));
        $balancer->addServer(dirname(__FILE__ . "/../"));

        $servers = $balancer->getServers();
        $this->assertTrue(is_array($servers));
        $this->assertEquals(dirname(__FILE__), $servers[1]);
    }

    public function testGetServerBy()
    {
        $balancer = new Balancer(5);
        $servers = array(1 => dirname(__FILE__), 2 => dirname(__FILE__ . "/../"));
        $balancer->addServer(dirname(__FILE__));
        $balancer->addServer(dirname(__FILE__ . "/../"));

        $server = $balancer->getServerBy('123');
        $this->assertEquals($servers[1], $server);
    }
}
