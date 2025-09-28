@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-accent-500 focus:ring-accent-500 rounded-md shadow-sm']) }}>{{ $slot }}</textarea>