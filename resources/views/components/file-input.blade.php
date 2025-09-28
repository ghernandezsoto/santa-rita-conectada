@props(['disabled' => false])

<input @disabled($disabled) type="file" {{ $attributes->merge(['class' => 'block w-full text-sm text-slate-500
    file:mr-4 file:py-2 file:px-4
    file:rounded-full file:border-0
    file:text-sm file:font-semibold
    file:bg-primary-50 file:text-primary-700
    hover:file:bg-primary-100
']) }}>