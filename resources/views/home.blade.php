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
    <!-- @auth -->
        <home-component></home-component>
    <!-- @endauth
    @guest
        <login-component></login-component>
    @endguest -->
</x-app-layout>
