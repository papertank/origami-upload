<?php namespace Origami\Upload;

interface FileInterface {

    public function getFileKey();
    public function getPath();
    public function getFilePath();
    public function getFilename();
    public function fileExists();
    public function isImage();
    public function getThumbnail();

}