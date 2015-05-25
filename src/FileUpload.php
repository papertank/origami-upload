<?php namespace Origami\Upload;

use Illuminate\Http\Request;

class FileUpload {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Request
     */
    private $request;

    public function __construct($name, Request $request = null)
    {
        $this->name = $name;
        $this->request = is_null($request) ? app('request') : $request;
    }

    public function process($path = null)
    {
        if ( is_null($path) ) $path = $this->getDefaultPath();

        if ( $file = $this->getUploadedFile($path) ) {
            return $file->getFilePath();
        }

        if ( $file = $this->getCurrentFile($path) ) {
            return $file->getFilePath();
        }

        return null;
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

        $file->move($path, $filename);

        return new File($path.'/'.$filename);
    }

    private function newFilename($extension)
    {
        return md5(uniqid(mt_rand())).'.'.strtolower($extension);
    }

    private function getCurrentFile($path)
    {
        if ( ! $this->request->has($this->name.'.uploaded') ) {
            return false;
        }

        if ( $this->request->has($this->name.'.delete') ) {
            return false;
        }

        $filename = $this->request->input($this->name.'.uploaded');

        return new File($path.'/'.$filename);
    }

    private function getDefaultPath()
    {
        return config('upload.path');
    }

}