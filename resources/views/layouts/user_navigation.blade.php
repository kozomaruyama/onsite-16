<v-app-bar flat dense color="amber" style="z-index:99;height:50px">
    <v-toolbar-title>
        <a class="navbar-brand" href="{{ url('/home') }}">
            {{ config('app.name', '') }}
        </a>
    </v-toolbar-title>
    <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/calendar') }}">
        <v-icon>mdi-calendar-month</v-icon>
    </v-btn>
    <!-- <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/gantto') }}">
        <v-icon>mdi-chart-timeline</v-icon>
    </v-btn> -->
    <v-spacer></v-spacer>
    @guest
        @if (Route::has('login'))
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        @endif
        @if (Route::has('register'))
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
        @endif
    @else
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    @endguest
</v-app-bar>