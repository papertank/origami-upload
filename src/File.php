<?php namespace Origami\Upload;

use Intervention\Image\ImageManagerStatic as Intervention;

class File implements FileInterface {

    protected $path;

    protected $is_image = null;
    protected $key = null;
    protected $image = null;

    public function __construct($path, $key = null)
    {
        $this->path = $path;
        $this->key = $key;
    }

    public function setFileKey($key)
    {
        $this->key = $key;
    }

    public function getFileKey()
    {
        return $this->key;
    }

    public function getPath()
    {
        return pathinfo($this->path, PATHINFO_DIRNAME);
    }

    public function getFilePath()
    {
        return $this->path;
    }

    public function getFilename()
    {
        return basename($this->path);
    }

    public function fileExists()
    {
        return file_exists($this->getFilePath());
    }

    public function isImage()
    {
        if ( $this->is_image === null ) {

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $this->getFilePath());
            finfo_close($finfo);

            $this->is_image = ( strpos($mime, 'image') === 0 );

        }

        return $this->is_image;
    }

    public function getThumbnail($size = 120)
    {
        $image = Intervention::make($this->getFilePath());

        $format = 'png';

        $image->fit($size)->encode($format);

        return 'data:image/'.$format.';base64,'.base64_encode($image);
    }
}