<?php

namespace Origami\Upload;

use Illuminate\Support\Facades\Facade;

class UploadFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'origami-upload.helper';
    }
}
