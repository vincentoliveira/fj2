<?php

namespace FJ\MainBundle\Tests\Service;

use FJ\MainBundle\Tests\FJWebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Tests\File\UploadedFileTest;

/**
 * Description of JokeServiceTest
 */
class JokeServiceTest extends FJWebTestCase
{

    /**
     * @var \FJ\MainBundle\Service\JokeService 
     */
    protected $service;
    
    /**
     * @var \FJ\MainBundle\Service\MediaService 
     */
    protected $mediaService;

    protected function setup()
    {
        parent::setup();
        $this->service = $this->container->get('fj.joke_service');
        $this->mediaService = $this->container->get('fj.media_service');
    }
    
    public function testJokeCreationKODescription()
    {
        $testFilePath = __DIR__ . '/../Fixtures/test.gif';
        $file = new UploadedFile($testFilePath, 'original.gif', null, filesize($testFilePath), UPLOAD_ERR_OK);
        
        $formData = array(
            'file' => $file,
        );
        $joke = $this->service->createJoke($formData);
        
        $this->assertNull($joke);
    }
    
    public function testJokeCreationKOMissingFile()
    {
        $formData = array(
            'description' => "Lorem ipsum sit amet",
        );
        $joke = $this->service->createJoke($formData);
        
        $this->assertNull($joke);
    }
    
    public function testJokeCreationKOBadFile()
    {
        $testFilePath = __DIR__ . '/../Fixtures/test_empty';
        $file = new UploadedFile($testFilePath, 'original.gif', null, filesize($testFilePath), UPLOAD_ERR_OK);

        $formData = array(
            'description' => "Lorem ipsum sit amet",
            'file' => $file,
        );
        $joke = $this->service->createJoke($formData);
        
        $this->assertNull($joke);
    }

    public function testJokeCreation()
    {
        $testFilePath = __DIR__ . '/../Fixtures/test.gif';
        $file = new UploadedFile($testFilePath, 'original.gif', null, filesize($testFilePath), UPLOAD_ERR_OK);

        $formData = array(
            'description' => "Lorem ipsum sit amet",
            'file' => $file,
        );
        $joke = $this->service->createJoke($formData);
        
        $this->assertInstanceOf('\FJ\MainBundle\Entity\Joke', $joke);
        $this->assertEquals($formData['description'], $joke->getDescription());
        $this->assertFileEquals($testFilePath, $this->mediaService->getAbsolutePath($joke->getMedia()));
    }

}
