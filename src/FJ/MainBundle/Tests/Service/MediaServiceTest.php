<?php

namespace FJ\MainBundle\Tests\Service;

use FJ\MainBundle\Tests\FJWebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of MediaServiceTest
 */
class MediaServiceTest extends FJWebTestCase
{

    /**
     * @var \FJ\MainBundle\Service\MediaService
     */
    protected $service;

    protected function setup()
    {
        parent::setup();
        $this->service = $this->container->get('fj.media_service');
    }

    public function testImportFromFileEmpty()
    {
        $media = $this->service->importFromFile(null);
        $this->assertNull($media);
    }

    public function testImportFromFile()
    {
        $testFilePath = __DIR__ . '/../Fixtures/test.gif';
        $file = new UploadedFile($testFilePath, 'original.gif', null, filesize($testFilePath), UPLOAD_ERR_OK);

        $media = $this->service->importFromFile($file);

        $this->assertInstanceOf('\FJ\MainBundle\Entity\Media', $media);
        $this->assertFileEquals($testFilePath, $this->service->getAbsolutePath($media));
    }

    public function testImportFromFileBadExtension()
    {
        $testFilePath = __DIR__ . '/../Fixtures/test_empty';
        $file = new UploadedFile($testFilePath, 'original', null, filesize($testFilePath), UPLOAD_ERR_OK);

        $media = $this->service->importFromFile($file);
        $this->assertNull($media);
    }

    public function testImportFromURLEmpty()
    {
        $media = $this->service->importFromURL(null);
        $this->assertNull($media);
    }

    public function testImportFromURLBad()
    {
        $media = $this->service->importFromURL('http://thisisabadurl.com/doesnotexist');
        $this->assertNull($media);
    }

    public function testImportFromURL()
    {
        $url = 'http://www.google.com/images/srpr/logo11w.png';
        $media = $this->service->importFromURL($url);
        $this->assertInstanceOf('\FJ\MainBundle\Entity\Media', $media);
        $this->assertFileExists($this->service->getAbsolutePath($media));
    }

}
