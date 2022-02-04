<nav class="navbar navbar-expand-lg navbar-light social-navbar">
    <div class="container">
        <a class="navbar-brand" href="/">Social App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                @guest()
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">
                            Register
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('accept-friendships.index') }}" class="nav-link">
                            Friend Requests
                        </a>
                    </li>
                    <notification-list></notification-list>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                            <li><a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0);"
                                   onclick="document.getElementById('logout').submit()">Logout</a>
                            </li>
                        </ul>
                    </li>
                    <form id="logout" action="{{ route('logout') }}" method="POST">@csrf</form>
                @endguest
            </ul>
        </div>
    </div>
</nav>
