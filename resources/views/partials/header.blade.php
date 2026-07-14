<div class="head-wrapper">
    <div class="head-cover">
        <img src="#" alt="School Admin System" class="cover-img">
    </div>

    <div class="header">

        <button class="toggle-btn" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        @if(isset($module))

        @php
            $headerTabs = config("modules.$module.header_tabs", []);
        @endphp
        <ul class="nav head-tabs">
            @foreach($headerTabs as $tab)
                <li class="nav-item">
                    <a class="nav-link tab-page {{ $tab['active'] ? 'active' : '' }}" id="{{ $tab['id'] ?? '' }}" href="#">
                        {{ $tab['label'] }}
                    </a>
                </li>
            @endforeach
            <!-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown"
                href="#">
                    Tab 1
                </a>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">
                            Option 1
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            Option 2
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    Tab 2
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown"
                href="#">
                    Tab 3
                </a>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">
                            Option 1
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            Option 2
                        </a>
                    </li>
                </ul>
            </li> -->

        </ul>

        @endif
    </div>
</div>