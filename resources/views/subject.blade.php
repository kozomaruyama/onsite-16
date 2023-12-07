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
    <!-- <subject-component :subjects="{{ $subjects }}"></subject-component> -->
    <subject-component 
        :subjects="{{ $subjects }}"
        :role="{{ $role }}"
    />
</x-app-layout>
