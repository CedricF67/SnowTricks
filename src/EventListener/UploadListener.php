<?php 

namespace App\EventListener;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\TrickPicture;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if ($entity instanceof User) {
            $file = $entity->getAvatar();
            // only upload new files
            if ($file instanceof UploadedFile) {
                $fileName = $this->uploader->upload($file, 'avatars');
                $entity->setAvatar($fileName);
            }
        }

        if ($entity instanceof Trick) {
            $files = $entity->getPictures();
            foreach ($files as $file) {
                // only upload new files
                if ($file->getFile() instanceof UploadedFile) {
                    $fileName = $this->uploader->upload($file->getFile(), 'pictures');
                    $file->setFile($fileName);
                }
            }
        }

        if ($entity instanceof TrickPicture) {
            if ($entity->getFile() instanceof UploadedFile) {
                $fileName = $this->uploader->upload($entity->getFile(), 'pictures');
                $entity->setFile($fileName);
            }
        }
    }
}