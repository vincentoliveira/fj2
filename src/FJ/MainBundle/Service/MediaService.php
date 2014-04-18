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
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return null|Media
     */
    public function importFromFile(UploadedFile $file = null) {
        return null;
    }
    
    public function getAbsolutePath(Media $media)
    {
        return null;
    }
}
