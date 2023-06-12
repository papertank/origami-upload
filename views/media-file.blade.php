<div class="file-upload">
    @if ( $current && $current->exists )
        <div class="current-file">
            @if ( $canRenderAsImage )
                {!! $current->img(isset($conversion) ? $conversion : '') !!}
            @else
                <p class="current-file-name">
                    <a href="{{ $current->getUrl() }}" target="_blank">{{ $current->name ?: $current->file_name }}</a>
                </p>
            @endif
            @if ( ! isset($delete) || $delete )
                <div class="form-check">
                    <input id="delete-{{ $name }}" type="checkbox" name="{{ $name }}[delete]" value="true"
                           class="form-check-input"/>
                    <label for="delete-{{ $name }}" class="form-check-label">Delete {{ $name }}</label>
                </div>
            @endif
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="{{ $name }}[file]" id="file-{{ $name }}">
            <label class="custom-file-label" for="file-{{ $name }}">Choose new file</label>
        </div>
    @else
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="{{ $name }}[file]" id="file-{{ $name }}">
            <label class="custom-file-label" for="file-{{ $name }}">Choose file</label>
        </div>
    @endif
    {{--<input type="file" name="{{ $name }}[file]" />--}}
</div>
