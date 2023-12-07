<x-app-layout>
    <x-slot name="header">
        @auth
            @if ($role>10) 
                @include('layouts.user_navigation')
            @else
                @include('layouts.navigation')
            @endif
        @endauth  
    </x-slot>
    <pay-component 
        :subcontracters="{{ $subcontracters }}" 
        :calendars="{{ $calendars }}" 
        :pay_calendars="{{ $pay_calendars }}" 
        :role="{{ $role }}"
        :person="{{ $person }}"
    >
    </pay-component>
</x-app-layout>