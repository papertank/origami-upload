<?php 

namespace Origami\Upload;

use Illuminate\Support\Facades\Facade;

class UploadFacade extends Facade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return 'upload.helper';
    }
}
