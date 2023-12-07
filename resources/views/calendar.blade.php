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
    <calendar-component :role="{{ $role }}" :user="{{ $user }}"/>
</x-app-layout>
