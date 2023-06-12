@if ( $current )
    <div class="current-file">
        @if ( \Illuminate\Support\Str::endsWith($current, ['jpg','jpeg','png','gif']) )
            <img src="{{ \Illuminate\Support\Facades\Storage::disk($disk)->url($current) }}" />
        @else
            <p class="current-file-name">{{ $current }}</p>
        @endif
        <div class="checkbox">
            <label>
                <input type="checkbox" name="{{ $name }}[delete]" value="true" /> Delete {{ $name }}
            </label>
        </div>
    </div>
    <p class="help-block">Upload a new file below or tick the box above to delete the current file.</p>
    <input type="hidden" name="{{ $name }}[uploaded]" value="{{ $current }}" />
@endif
<input type="file" name="{{ $name }}[file]" />
