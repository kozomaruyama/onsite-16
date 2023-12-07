<x-app-layout>
    <x-slot name="header">
        @auth
            @if ($role==1) 
                @include('layouts.navigation')
            @else
                @include('layouts.user_navigation')
            @endif
        @endauth        
    </x-slot>
    <gantto-component />
</x-app-layout>
