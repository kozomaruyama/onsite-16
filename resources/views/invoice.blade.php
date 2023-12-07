<x-app-layout>
    <x-slot name="header">
        {{-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('home') }}
        </h2> --}}
    </x-slot>
    <invoice-component 
        :invoices="{{ $invoices }}" 
        :clients="{{ $clients }}" 
        :calendars="{{ $calendars }}"
    ></invoice-component>
</x-app-layout>
