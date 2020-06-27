<!-- Header start -->
<header>
    <nav>
        <div class="container nav">
            <div class="nav__brand">
                <a href="#" class="nav__brand__logo"><img src="{{ ('/frontend/assets/images/fulllogo.png') }}" alt=""
                        height="auto" /></a>
            </div>
            <div class="nav__menu">
                <div class="menu__container">
                    <ul class="menu__list">
                        <li class="menu__list__item"><a href="/" class="menu__list__link">Home</a></li>
                        <li class="menu__list__item"><a href="/about" class="menu__list__link">About</a></li>
                        <li class="menu__list__item"><a href="/faq" class="menu__list__link">FAQ</a></li>
                        <li class="menu__list__item"><a href="/contact" class="menu__list__link">Contact
                                Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="nav__button__container">
                 @if(!isset($_COOKIE['api_token']))
                <button class="nav__button "><a href="/admin/login" class="nav__button__link">Log In</a></button>
                <button class="nav__button btn-nav-active"><a href="/admin/register"
                        class="nav__button__link__active">Sign
                        Up</a></button>
                @elseif(isset($_COOKIE['api_token']))
                    <button class="nav__button btn-nav-active"><a href="#"
                        class="nav__button__link__active">Logout</a></button>
                @endif
            </div>
            <div class="hamburger-container">
                <div class="hamburger__menu">
                    <div class="menubar menu-bar-one"></div>
                    <div class="menubar menu-bar-two"></div>
                    <div class="menubar menu-bar-three"></div>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="mobile-menu">
            <div class="close-mobile-menu">
                <i class="fas fa-times"></i>
            </div>
            <div class="mobile__nav__menu">
                <div class="mobile__menu__container">
                    <ul class="mobile__menu__list">
                        <li class="mobile__menu__list__item"><a href="/"
                                class="mobile__menu__list__link">Home</a>
                        </li>
                        <li class="mobile__menu__list__item"><a href="/about"
                                class="mobile__menu__list__link">About</a>
                        </li>
                        <li class="mobile__menu__list__item"><a href="/faq" class="mobile__menu__list__link">FAQ</a>
                        </li>
                        <li class="mobile__menu__list__item"><a href="/contact"
                                class="mobile__menu__list__link">Contact Us</a></li>
                    </ul>
                </div>
                <div class="mobile__nav__button__container">
                    @if(!isset($_COOKIE['api_token']))
                    <button class="mobile__nav__button"><a href="/admin/login" class="mobile__nav__button__link">Log
                            In</a></button>
                    <button class="mobile__nav__button"><a href="/admin/register" class="mobile__nav__button__link">Sign
                            Up</a></button>
                    @elseif(isset($_COOKIE['api_token']))
                   <button class="mobile__nav__button"><a href="#" class="mobile__nav__button__link">Logout</a></button>
                @endif
                </div>
            </div>
        </div>
    </nav>

</header>
<!-- header end -->
