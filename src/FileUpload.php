<?php

namespace Origami\Upload;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string|null
     */
    protected $disk = null;

    public function __construct($name, Request $request = null)
    {
        $this->name = $name;
        $this->request = is_null($request) ? app('request') : $request;
    }

    public function process($directory = null, $current = null)
    {
        $path = $this->getUploadedFile($directory);

        if ( ! $path ) {
            $path = $this->getCurrentFile($current);
        }

        if ( $current && ( $path != $current ) ) {
            Storage::disk($this->disk)->delete($current);
        }

        return $path;
    }

    public function getDisk()
    {
        return $this->disk;
    }

    public function setDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }

    protected function getUploadedFile($path)
    {
        $key = $this->name.'.file';

        if ( ! $this->request->hasFile($key) ) {
            return false;
        }

        $file = $this->request->file($key);

        if ( ! $file->isValid() ) {
            return false;
        }

        return Storage::disk($this->disk)->putFile($path, $this->request->file($key));
    }

    protected function getCurrentFile($current = null)
    {
        if ( $this->request->has($this->name.'.delete') ) {
            return false;
        }

        return $current;
    }

}
