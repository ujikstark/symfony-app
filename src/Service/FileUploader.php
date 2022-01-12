<?php

namespace App\Service;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {


    #[ContainerInterface]
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function uploadFile(UploadedFile $file) 
    {
        $fileName = md5(uniqid() . '.' . $file->guessClientExtension());

        $file->move(
            // TODO: get target directory ,
            $this->container->getParameter('uploads_dir'),
            $fileName
        );

        return $fileName;
    }
}