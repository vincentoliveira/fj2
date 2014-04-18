<?php

namespace FJ\MainBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use FJ\MainBundle\Entity\Media;

/**
 * Description of MediaService
 * 
 * @Service("fj_media_service")
 */
class MediaService
{
    /**
     * Import media from file
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return null|Media Imported media
     */
    public function importFromFile(UploadedFile $file = null) {
        if ($file === null) {
            return null;
        }
        
        $media = new Media();
        
        return $media;
    }
    
    /**
     * Get absolute path of a media
     * 
     * @param \FJ\MainBundle\Entity\Media $media
     * @return string
     */
    public function getAbsolutePath(Media $media)
    {
        return null;
    }
}
