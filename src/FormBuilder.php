<?php namespace Origami\Upload;

use Illuminate\Session\Store as Session;

class FormBuilder {

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