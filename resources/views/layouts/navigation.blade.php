<v-app-bar flat dense color="amber" style="z-index:99;height:50px">
    <v-toolbar-title class="mr-2">
        <a class="navbar-brand" href="{{ url('/home') }}">
            <!-- {{ config('app.name', '') }} -->
            <img src="/img/logo1.png" height="36.4" width="40" align="middle">
        </a>
    </v-toolbar-title>
    <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/calendar') }}">
        <v-icon>mdi-calendar-month</v-icon>
    </v-btn>
    <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/subject') }}">
        <v-icon>mdi-animation-outline</v-icon>
    </v-btn>
    <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/bill') }}">
        <v-icon>mdi-calculator-variant-outline</v-icon>
    </v-btn>
    <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/pay') }}">
        <v-icon>mdi-cash-register</v-icon>
    </v-btn>

    <!-- <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/gantto') }}">
        <v-icon>mdi-chart-timeline</v-icon>
    </v-btn> -->
    <!-- <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/master') }}">
        <v-icon>mdi-toolbox</v-icon>
    </v-btn> -->
    <!-- <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/setting') }}">
        <v-icon>mdi-cog-outline</v-icon>
    </v-btn> -->
    <!-- <v-btn class="mx-2" depressed small fab elevate-on-scroll href="{{ url('/test') }}">
        <v-icon>mdi-cog-outline</v-icon>
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
            <!-- <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a> -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <v-list dense>
                <!-- <v-list-item-group v-model="selectedItem" color="primary"> -->
                <v-list-item-group color="primary">
                    <v-list-item href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                    >
                        <v-list-item-icon>
                            <v-icon dense>mdi-account-check</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>ログアウト</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>

            <hr>



            <v-list dense>
                <!-- <v-list-item-group v-model="selectedItem" color="primary"> -->
                <v-list-item-group color="primary">
                    <v-list-item href="{{ url('/master') }}">
                        <v-list-item-icon>
                            <v-icon dense>mdi-toolbox</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>マスター管理</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>

            <!-- <v-list dense>
                <v-list-item-group color="primary">
                    <v-list-item href="https://lin.ee/0TToEun" target="_blank">
                        <v-list-item-icon>
                            <v-icon dense>mdi-chat-plus</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <img src="https://scdn.line-apps.com/n/line_add_friends/btn/ja.png" alt="友だち追加" style="width: 80px;height: auto;" border="0">
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list> -->

        </div>
    @endguest
</v-app-bar>
