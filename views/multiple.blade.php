
<div class="upload-buttons">
    <a id="uploader{{ $upload_suffix }}" class="btn btn-upload btn-default" href="javascript:;">Browse Files</a>
</div>

<div id="upload-box{{ $upload_suffix }}" class="uploading-box">

    <small class="upload-instructions">click the button above or drag and drop into this box to start uploading photos. when you're done, click the save button below.</small>

    <div id="upload-queue{{ $upload_suffix }}">
        <div class="alert alert-info">
            Your browser doesn't have Flash, Silverlight or HTML5 support. Please switch to the latest version of Chrome, Firefox or Safari.
        </div>
    </div>

    @if ( $files )
        @foreach ( $files as $file )
            <div class="upload-item upload-item-existing">
                <a class="upload-cancel close">×</a>
                @if ( $file->isImage() )
		            <div class="photo"><img src="{{ $file->getThumbnail() }}" /></div>
		        @else
		            <span class="filename">{{ $file->getFilename() }}</span>
		        @endif
                <input type="hidden" name="{{ $name }}[]" value="{{ $file->getFileKey() }}" />
            </div>
        @endforeach
    @endif

</div>