<?php

namespace FJ\MainBundle\Tests\Service;

use FJ\MainBundle\Tests\FJWebTestCase;
use FJ\MainBundle\Service\MediaService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function testImportFromFile()
    {
        $testFilePath = __DIR__ . '/../Fixtures/test.gif';
        $file = new UploadedFile($testFilePath, 'original.gif', null, filesize($testFilePath), UPLOAD_ERR_OK);

        $media = $this->service->importFromFile($file);
        
        $this->assertInstanceOf('\FJ\MainBundle\Entity\Media', $media);
        $this->assertFileEquals($testFilePath, $this->service->getAbsolutePath($media));
    }

}
