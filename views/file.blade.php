@if ( $file && $file->fileExists() )
    <div class="current-file">
        @if ( $file->isImage() )
            <img src="{{ $file->getThumbnail() }}" />
        @else
            <p class="current-file-name">{{ $file->getFilename() }}</p>
        @endif
        <div class="checkbox">
            <label>
                <input type="checkbox" name="{{ $name }}[delete]" value="true" /> Delete {{ $name }}
            </label>
        </div>
    </div>
    <p class="help-block">Upload a new file below or tick the box above to delete the current file.</p>
    <input type="hidden" name="{{ $name }}[uploaded]" value="{{ $file->getFilename() }}" />
@endif
<input type="file" name="{{ $name }}[file]" />
