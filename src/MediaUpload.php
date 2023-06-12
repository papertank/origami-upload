<?php

namespace Origami\Upload;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;

class MediaUpload
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var HasMedia
     */
    protected $model;

    /**
     * @var string
     */
    protected $collection;

    /**
     * @var Request
     */
    protected $request;

    public function __construct($name, HasMedia $model, $collection = null)
    {
        $this->name = $name;
        $this->model = $model;
        $this->collection = $collection;
        $this->request = app('request');
    }

    public function process()
    {
        if ($this->deleteFile()) {
            return null;
        }

        if ($media = $this->uploadFile()) {
            return $media;
        }

        return null;
    }

    protected function deleteFile()
    {
        if (! $this->request->has($this->name.'.delete')) {
            return false;
        }

        $this->model->clearMediaCollection($this->collection);

        return true;
    }

    protected function uploadFile()
    {
        $key = $this->name.'.file';

        if (! $this->request->hasFile($key)) {
            return false;
        }

        $file = $this->request->file($key);

        if (! $file->isValid()) {
            return false;
        }

        $media = $this->model->addMediaFromRequest($key)
            ->toMediaCollection($this->collection);

        return $media;
    }
}
