<?php 

namespace Origami\Upload;

use Illuminate\Session\Store as Session;
use Origami\Upload\FileUpload;

class UploadHelper {

    /**
     * The session store implementation.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    public function file($name = 'file', $path = null)
    {
        $file = ( ! is_null($path) ? new File($path) : null );

        return view('upload::file', ['name' => $name, 'file' => $file])->render();
    }

    public function multiple($id = null, array $files = [], $name = 'media')
    {
        $upload_suffix = ( ! is_null($id) ? '-'.$id : '' );

        if ( $files ) {
            $files = array_map(function($key, $path){
                return new File($path, $key);
            }, array_keys($files), $files);
        }

        return view('upload::multiple', ['name' => $name, 'files' => $files, 'upload_suffix' => $upload_suffix])->render();
    }

    public function processFile($name = 'file', $path = null)
    {
        $file = new FileUpload($name);
        $path = $file->process($path);

        return ( ! is_null($path) ? basename($path) : null );
    }

    /**
     * Set the session store implementation.
     *
     * @param  \Illuminate\Session\Store  $session
     * @return $this
     */
    public function setSessionStore(Session $session)
    {
        $this->session = $session;

        return $this;
    }

}