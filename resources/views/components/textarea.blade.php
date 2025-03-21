<div>
    <label for="{{ $id }}"
        class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
        class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-customOrangeDark focus:border-customOrangeDark block w-full p-2.5 h-[86px]"
        {{ $required }}>{{ old($name, $value) }}</textarea>
</div>
