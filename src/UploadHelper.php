<?php

namespace Origami\Upload;

use Exception;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Image;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadHelper {

    protected $disk = 'public';

    public function setDefaultDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }

    public function formFile($name = 'file', $current = null, $disk = null)
    {
        return view('upload::file', ['name' => $name, 'current' => $current, 'disk' => null])->render();
    }

    public function formMediaFile($name = 'file', Media $current = null)
    {
        if ( ! class_exists(Media::class) ) {
            throw new Exception('spatie/laravel-medialibrary package is missing');
        }

        $canRenderAsImage = $current ? (new Image())->canHandleMime($current->mime_type) : false;
        return view('upload::media-file', ['name' => $name, 'current' => $current, 'canRenderAsImage' => $canRenderAsImage])->render();
    }

    public function processFileUpload($name = 'file', $current = null, $directory = null, $disk = null)
    {
        $file = new FileUpload($name);

        if ( $disk || $this->disk ) {
            $file->setDisk($disk ?: $this->disk);
        }

        return $file->process($directory, $current);
    }

    public function processMediaUpload($name, HasMedia $model, $collection = null)
    {
        if ( ! class_exists(Media::class) ) {
            throw new Exception('spatie/laravel-medialibrary package is missing');
        }

        $file = new MediaUpload($name, $model, $collection);
        $media = $file->process();

        return $media ?: null;
    }

}
