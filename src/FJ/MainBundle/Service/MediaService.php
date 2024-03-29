<?php

namespace FJ\MainBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use FJ\MainBundle\Entity\Media;

/**
 * Description of MediaService
 * 
 * @Service("fj.media_service")
 */
class MediaService
{
    protected static $acceptedMimeType = array(
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpg',
        'gif' => 'image/gif',
        'png' => 'image/png',
    );

    /**
     * Entity Manager
     * 
     * @Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * Kernel
     * 
     * @Inject("kernel")
     * @var \Symfony\Component\HttpKernel\Kernel
     */
    public $kernel;
    
    /**
     * Import media from file
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return null|Media Imported media
     */
    public function importFromFile(UploadedFile $file = null) {
        if (!$this->fileIsValid($file)) {
            return null;
        }
        
        // copy to upload dir with unique filename
        $uploadDir = $this->getUploadDir();
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $tmpPath = tempnam($uploadDir, '');
        $dstPath = $tmpPath . $file->guessExtension();
        copy($file->getPathName(), $dstPath);
        unlink($tmpPath);
        
        $media = new Media();
        $media->setPath(basename($dstPath));
        
        $this->em->persist($media);
        $this->em->flush();

        return $media;
    }
    
    /**
     * Import media from URL (downlaod)
     * 
     * @param string $url
     * @return null|Media Imported media
     */
    public function importFromURL($url) {
        $tmpPath = '/tmp/' . sha1(uniqid(mt_rand(), true));
        if (!$this->downloadMedia($url, $tmpPath) || !$this->downloadFileIsValid($tmpPath)) {
            return null;
        }
        
        // copy to upload dir with unique filename
        $uploadDir = $this->getUploadDir();
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $tmpPath = tempnam($uploadDir, '');
        $dstPath = $tmpPath . basename(mime_content_type($tmpPath));
        copy($tmpPath, $dstPath);
        unlink($tmpPath);
        
        $media = new Media();
        $media->setPath(basename($dstPath));
        
        $this->em->persist($media);
        $this->em->flush();

        return $media;
    }
    
    /**
     * Download $mediaUrl to $path
     * 
     * @param string $mediaUrl
     * @param string $dir
     * @return boolean
     */
    protected function downloadMedia($mediaUrl, $path)
    {
        $ch = curl_init($mediaUrl);
        $fp = fopen($path, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        fclose($fp);
        
        return $httpStatus == 200;
    }
    
    /**
     * Get absolute path of a media
     * 
     * @param \FJ\MainBundle\Entity\Media $media
     * @return string
     */
    public function getAbsolutePath(Media $media)
    {
        return $this->getUploadDir() . $media->getPath();
    }
    
    protected function getUploadDir()
    {
        return $this->kernel->getRootDir() . '/../web/media/';
    }
    
    protected function fileIsValid(UploadedFile $file = null)
    {
        return $file !== null && $this->mimeTypeIsValid($file->getMimeType());
    }
    
    protected function downloadFileIsValid($path)
    {
        return $this->mimeTypeIsValid(mime_content_type($path));
    }
    
    protected function mimeTypeIsValid($mimeType)
    {
        return in_array($mimeType, self::$acceptedMimeType);
    }
}
