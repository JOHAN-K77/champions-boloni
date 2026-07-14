<div class="sidebar" id="sidebar">

    <div class="logo-area">

        <div class="logo-box">
            <span class="logo-text">Logo 1</span>
        </div>

        <div class="logo-box">
            <span class="logo-text">Logo 2</span>
        </div>

    </div>

    <ul>
    @foreach(config('ui.sidebar_menu') as $item)
        @if($item['type'] === 'section')
            <div class="menu-title menu-text">
                {{ $item['label'] }}
            </div>
        @endif
        
        @if($item['type'] === 'item')
            <li>
                <a href="{{ route($item['route']) }}"
                   class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    <span>
                        <i class="{{ $item['icon'] }}"></i>
                        <span class="menu-text ms-2">
                            {{ $item['label'] }}
                        </span>
                    </span>
                </a>
            </li>
        @endif

        @if($item['type'] === 'dropdown')

            @php

            $hasActiveChild = false;

            if(isset($item['children'])) {
                foreach($item['children'] as $child) {
                    if(request()->routeIs($child['route'])) {
                        $hasActiveChild = true;
                        break;
                    }
                }
            }

            @endphp

            @if(!isset($item['route']))

                <li class="{{ $hasActiveChild ? 'menu-open' : '' }}">
                    
                    <a class="menu-dropdown">
                        <span>
                            <i class="{{ $item['icon'] }}"></i>
                            <span class="menu-text ms-2">{{ $item['label'] }}</span>
                        </span>

                        @if(isset($item['children']))
                            @if(isset($item['route']))
                                <i class="{{ $hasActiveChild && 'bi bi-chevron-down' }}"></i>
                            @else
                                <i class="bi {{ $hasActiveChild ? 'bi-chevron-down' : 'bi-chevron-right' }} arrow-icon"></i>
                            @endif
                        @endif
                    </a>

                    @if(isset($item['children']))

                    <div class="submenu">
                        @foreach($item['children'] as $child)

                            @php

                            $visible = true;

                            if(isset($child['visible_on'])) {
                                $visible = request()->routeIs($child['visible_on']);
                            }
                            @endphp

                            @if($visible)
                                <a href="{{ route($child['route']) }}" class="{{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                    {{ $child['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </li>
            @else
                @if(!$hasActiveChild)
                    <li>
                        <a href="{{ route($item['route']) }}"
                        class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                            <span>
                                <i class="{{ $item['icon'] }}"></i>
                                <span class="menu-text ms-2">
                                    {{ $item['label'] }}
                                </span>
                            </span>
                        </a>
                    </li>
                @else
                    <li class="menu-open bg-secondary">
                        <a href="{{ route($item['route']) }}">
                            <span>
                                <i class="{{ $item['icon'] }}"></i>
                                <span class="menu-text ms-2">
                                    {{ $item['label'] }}
                                </span>
                            </span>

                            <i class="bi bi-chevron-down"></i>
                        </a>

                        <div class="submenu">
                            @foreach($item['children'] as $child)

                                @php

                                $visible = true;

                                if(isset($child['visible_on'])) {
                                    $visible = request()->routeIs($child['visible_on']);
                                }
                                @endphp

                                @if($visible)
                                    <a href="{{ route($child['route']) }}" class="{{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                        {{ $child['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </li>
                @endif
            @endif
        @endif
    @endforeach

    </ul>

</div>