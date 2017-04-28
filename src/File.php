<?php 

namespace Origami\Upload;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Intervention;

class File implements FileInterface {

    protected $path;

    protected $key = null;
    protected $image = null;
    protected $disk = null;

    public function __construct($path, $key = null, $disk = null)
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

    public function getFileContents()
    {
        return Storage::disk($this->disk)->get($this->getFilePath());
    }

    public function fileExists()
    {
        return Storage::disk($this->disk)->exists($this->getFilePath());
    }

    public function isImage()
    {
        if ($this->image === null) {
            $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->getFileContents());

            $this->image = (strpos($mime, 'image') === 0);
        }

        return $this->image;
    }

    public function getThumbnail($size = 120)
    {
        $image = Intervention::make($this->getFilePath());

        $format = 'png';

        $image->fit($size)->encode($format);

        return 'data:image/'.$format.';base64,'.base64_encode($image);
    }
}