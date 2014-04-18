<?php

namespace FJ\MainBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

//use Symfony\Component\Console\Input\ArrayInput;
//use Symfony\Component\Console\Output\ConsoleOutput;
//use Behat\Behat\Console\BehatApplication;

abstract class FJWebTestCase extends WebTestCase
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var HttpKernelInterface
     */
    protected $kern;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Application
     */
    protected $app;

    protected function setup()
    {
        parent::setup();
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->kern = $this->client->getKernel();
        $this->container = $this->client->getContainer();
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    protected function tearDown()
    {
        //Close & unsets
        $this->em->getConnection()->close();
        $this->em->close();
        parent::tearDown();
        unset($this->em);
        unset($this->container);
        unset($this->kern);
        unset($this->client);
        //Nettoyage des mocks
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }

//    public function scenariosMeetAcceptanceCriteria($bundleName)
//    {
//        $input = new ArrayInput(array(
//            '--format' => 'progress',
//            'features' => '@'.$bundleName
//        ));
//        $output = new ConsoleOutput();
//        $app = new BehatApplication('DEV');
//        $app->setAutoExit(false);
//        $result = $app->run($input, $output);
//        $this->assertEquals(0, $result, 'Au moins un des scénarios Behat ne passe pas dans '.$bundleName.'');
//    }

    /**
     * Truncate table
     *
     * @param $entity
     */
    protected function truncateTable($entity)
    {
        $cmd = $this->em->getClassMetadata($entity);
        $connection = $this->em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollback();
        }
    }

}
