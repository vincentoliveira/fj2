<?php

namespace FJ\MainBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of MediaService
 * 
 * @Service("fj_media_service")
 */
class MediaService
{
    public function importFromFile(UploadedFile $file = null) {
        return NULL;
    }
}
