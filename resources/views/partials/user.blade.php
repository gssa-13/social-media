<div class="card border-0 bg-light shadow-sm">
    <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
    <div class="card-body">
        @if(Auth::id() === $user->id)
            <div class="card-title">
                <a href="{{ route('users.show', $user) }}">
                    {{ $user->name  }}
                </a>
            </div>
        @else
            <div class="card-title">
                <a href="{{ route('users.show', $user) }}">
                    {{ $user->name  }}
                </a>
            </div>
            <request-friendship-btn
                dusk="request-friendship"
                :recipient="{{ $user }}"/>
        @endif
    </div>
</div>
