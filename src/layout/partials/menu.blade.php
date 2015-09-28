<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <br>
            <li class="start {{ isset($menu) && $menu == 'menu1' ? 'active open' : '' }}">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Menu 1</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{ isset($submenu) && $submenu == 'submenu1' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-user"></i> SubMenu 1.1</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'submenu2' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-users"></i> SubMenu 1.2</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'submenu3' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-users"></i> SubMenu 1.3</a>
                    </li>
                </ul>
            </li>
            <li class="{{ isset($menu) && $menu == 'menu2' ? 'active open' : '' }}">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Menu 2</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{ isset($submenu) && $submenu == 'submenu1' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-user"></i> SubMenu 2.1</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'submenu2' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-users"></i> SubMenu 2.2</a>
                    </li>
                </ul>
            </li>
            <li class="{{ isset($menu) && $menu == 'menu3' ? 'active open' : '' }}">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Menu 2</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{ isset($submenu) && $submenu == 'submenu1' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-user"></i> SubMenu 3.1</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'submenu2' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-users"></i> SubMenu 3.2</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->