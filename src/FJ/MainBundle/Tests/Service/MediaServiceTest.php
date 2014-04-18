<?php

namespace FJ\MainBundle\Tests\Service;

use FJ\MainBundle\Tests\FJWebTestCase;
use FJ\MainBundle\Service\MediaService;

/**
 * Description of newPHPClass
 *
 * @author CL5044
 */
class MediaServiceTest extends FJWebTestCase
{
    /**
     * @var MediaService 
     */
    protected $service;


    protected function setup()
    {
        parent::setup();
        $this->service = $this->container->get('fj_media_service');
    }
    
    public function testImportFromFileEmpty()
    {
        $result = $this->service->importFromFile(null);
        $this->assertNull($result);
    }
}
