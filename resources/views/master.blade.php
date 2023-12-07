<x-app-layout>
    <x-slot name="header" class="h-100">
        @auth
            @if ($user->role>10) 
                @include('layouts.user_navigation')
            @else
                @include('layouts.navigation')
            @endif
        @endauth  
    </x-slot>
    <master-component :user="{{ $user }}"/>
</x-app-layout>
