<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file, string $targetSubDir)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getTargetDir($targetSubDir), $fileName);
        
        return $fileName;
    }
    
    public function getTargetDir(string $targetSubDir)
    {
        return $this->targetDir . $targetSubDir;
    }
}