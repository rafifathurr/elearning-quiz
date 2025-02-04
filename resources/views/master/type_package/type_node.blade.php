<li style="margin-left: {{ $level * 20 }}px;">
    <input type="radio" name="id_parent" value="{{ $type->id }}" id="type_{{ $type->id }}"
        {{ isset($selectedType) && $selectedType == $type->id ? 'checked' : '' }}>

    <label for="type_{{ $type->id }}"> {{ $type->name }} </label>

    @if ($type->children->isNotEmpty())
        <ul style="list-style-type: none; padding-left: 10px;">
            @foreach ($type->children as $child)
                @include('master.type_package.type_node', [
                    'type' => $child,
                    'level' => $level + 1,
                    'selectedType' => $selectedType ?? null, // Pastikan dikirim ke child
                ])
            @endforeach
        </ul>
    @endif
</li>
