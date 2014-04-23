<?php

namespace FJ\MainBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use FJ\MainBundle\Entity\Joke;
use FJ\MainBundle\Enum\JokeStatusEnum;

/**
 * Description of JokeService
 * 
 * @Service("fj.joke_service")
 */
class JokeService
{
    /**
     * Media Service
     * 
     * @Inject("fj.media_service")
     * @var MediaService
     */
    public $mediaService;

    /**
     * Entity Manager
     * 
     * @Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;
    
    /**
     * CreateJoke
     * 
     * @param array $formData
     * @return \FJ\MainBundle\Entity\Joke
     */
    public function createJoke(array $formData, $status = JokeStatusEnum::PENDING) {
        
        if ((!isset($formData['file']) && !isset($formData['file_url'])) || !isset($formData['description'])) {
            return null;
        }
        
        if (isset($formData['file'])) {
            $media = $this->mediaService->importFromFile($formData['file']);
        } else {
            $media = $this->mediaService->importFromURL($formData['file_url']);
        }
        
        if ($media === null) {
            return null;
        }
        
        $joke = new Joke();
        $joke->setDescription($formData['description']);
        $joke->setMedia($media);
        $joke->setStatus($status);
        
        $this->em->persist($joke);
        $this->em->flush();

        return $joke;
    }
    
}
