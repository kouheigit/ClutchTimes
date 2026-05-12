{{-- フィルターバーパーシャル --}}
@props(['filters' => []])

<div class="mb-4 flex flex-wrap gap-2">
    @foreach($filters as $filter)
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $filter['label'] }}</label>
        <select name="{{ $filter['name'] }}" 
                onchange="this.form.submit()"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            @foreach($filter['options'] as $option)
            <option value="{{ $option['value'] }}" {{ request($filter['name']) == $option['value'] ? 'selected' : '' }}>
                {{ $option['label'] }}
            </option>
            @endforeach
        </select>
    </div>
    @endforeach
</div>

