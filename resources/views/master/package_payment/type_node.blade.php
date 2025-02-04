<li style="margin-left: {{ $level * 20 }}px;">
    <input type="radio" name="id_type_package" value="{{ $type->id }}" id="type_{{ $type->id }}"
        {{ $type->children->isNotEmpty() ? 'disabled' : '' }} {{-- Disable jika punya child --}}
        {{ isset($selectedType) && $selectedType == $type->id ? 'checked' : '' }}> {{-- Checked jika dipilih --}}

    <label for="type_{{ $type->id }}"
        style="{{ $type->children->isNotEmpty() ? 'color: gray; cursor: not-allowed;' : '' }}">
        {{ $type->name }}
    </label>

    @if ($type->children->isNotEmpty())
        <ul style="list-style-type: none; padding-left: 10px;">
            @foreach ($type->children as $child)
                @include('master.package_payment.type_node', [
                    'type' => $child,
                    'level' => $level + 1,
                    'selectedType' => $selectedType ?? null,
                ])
            @endforeach
        </ul>
    @endif
</li>
