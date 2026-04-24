@php
    $items = [
        [
            'label' => 'Employees',
            'subheadings' => [
                [
                    'route' => 'employees.index',
                    'label' => 'Employees List',
                    'icons' => 'users'
                ],
                [
                    'route' => 'documents.offer-letters.index',
                    'label' => 'Offer Letters',
                    'icons' => ''
                ],
                [
                    'route' => 'documents.joining-letters.index',
                    'label' => 'Confirmation Letters',
                    'icons' => ''
                ],
                // [
                //     'route' => 'documents.experience-letters.index',
                //     'label' => 'Experience Letters',
                //     'icons' => ''
                // ],
                // [
                //     'route' => 'documents.salary-slips.index',
                //     'label' => 'Salary Slips',
                //     'icons' => ''
                // ],


            ],
        ],
        [
            'label' => 'Settings',
            'subheadings' => [
                [
                    'route' => 'allowance.index',
                    'label' => 'Allowance List',
                    'icons' => 'users'
                ]
            ],
        ],
    ];
@endphp
<nav class="app-nav" aria-label="Main">
    <div class="dropdown-container">
        @foreach ($items as $item)
        <div class="dropdown">
            <button class="btn dropdown-toggle nav-drop-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $item['label'] }}
            </button>
            <ul class="dropdown-menu">
                @foreach ($item['subheadings'] as $shortcut)
                    <li><a class="dropdown-item app-nav__link" href="{{ route($shortcut['route']) }}">{{ $shortcut['label'] }}</a></li>
                @endforeach
            </ul>
        </div>
        </a>
    @endforeach
    </div>
    <div class="nav-right d-flex">

    @auth
        <div class="nav-user pe-3">
            <i class="fa fa-user user-icon pe-2"></i>

            <span class="user-name">
                {{ auth()->user()->user_name ?? auth()->user()->email }}
            </span>
        </div>
    @endauth

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            Sign out
        </button>
    </form>

</div>
</nav>