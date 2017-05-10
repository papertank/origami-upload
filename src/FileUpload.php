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
    private $request;

    /**
     * @var string|null
     */
    private $disk = null;

    public function __construct($name, Request $request = null)
    {
        $this->name = $name;

        $this->request = is_null($request) ? app('request') : $request;
    }

    public function process($path = null, $current = null)
    {
        if ( is_null($path) ) $path = $this->getDefaultPath();

        if ( $file = $this->getUploadedFile($path) ) {
            return $file->getFilePath();
        }

        if ( $file = $this->getCurrentFile($path, $current) ) {
            return $file->getFilePath();
        }

        return null;
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

    private function getUploadedFile($path)
    {
        $key = $this->name.'.file';

        if ( ! $this->request->hasFile($key) ) {
            return false;
        }

        $file = $this->request->file($key);

        if ( ! $file->isValid() ) {
            return false;
        }

        $filename = $this->newFilename($file->getClientOriginalExtension());

        $contents = file_get_contents($file->getRealPath());

        if ( $image = $this->orientatedImage($file) ) {
            $contents = $image;
        }

        Storage::disk($this->disk)->put($path.'/'.$filename, $contents);

        return new File($path.'/'.$filename);
    }

    private function orientatedImage(UploadedFile $file)
    {
        $mime = $file->getClientMimeType() ?: $file->getMimeType();

        if ( ! $mime OR ! in_array(strtolower($mime), ['image/jpeg', 'image/jpg']) ) {
            return false;
        }

        $image = Image::make($file->getRealPath());

        $orientation = $image->exif('Orientation');

        if ( is_null($orientation) ) {
            return false;
        }

        return $image->orientate()->encode();
    }

    private function newFilename($extension)
    {
        return md5(uniqid(mt_rand())).'.'.strtolower($extension);
    }

    private function getCurrentFile($path, $current = null)
    {
        if ( $this->request->has($this->name.'.delete') ) {
            return false;
        }

        if ( ! $current && ! $this->request->has($this->name.'.uploaded') ) {
            return false;
        }

        $filename = $current ?: $this->request->input($this->name.'.uploaded');

        return new File($path.'/'.$filename);
    }

    private function getDefaultPath()
    {
        return config('upload.path');
    }

}
