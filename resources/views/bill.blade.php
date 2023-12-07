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
    <bill-component 
        :clients="{{ $clients }}" 
        :subcontracters="{{ $subcontracters }}" 
        :calendars="{{ $calendars }}" 
        :invoice_calendars="{{ $invoice_calendars }}" 
        :bill_calendars="{{ $bill_calendars }}" 
        :role="{{ $role }}"
        :person="{{ $person }}"
    >
    </bill-component>
</x-app-layout>
