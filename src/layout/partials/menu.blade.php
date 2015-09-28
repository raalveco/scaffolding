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
            <li class="start {{ isset($menu) && $menu == 'users' ? 'active open' : '' }}">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Usuarios</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{ isset($submenu) && $submenu == 'users' ? 'active' : '' }}">
                        <a href="/admin/users"><i class="fa fa-user"></i> Usuarios</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'sellers' ? 'active' : '' }}">
                        <a href="/admin/users/sellers"><i class="fa fa-user"></i> Vendedores</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'roles' ? 'active' : '' }}">
                        <a href="/admin/roles"><i class="fa fa-lock"></i> Roles</a>
                    </li>
                </ul>
            </li>
            <li class="start {{ isset($menu) && $menu == 'products' ? 'active open' : '' }}">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Productos</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{ isset($submenu) && $submenu == 'new_product' ? 'active' : '' }}">
                        <a href="/admin/products/create"><i class="fa fa-user"></i> Nuevo Producto</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'products' ? 'active' : '' }}">
                        <a href="/admin/products"><i class="fa fa-user"></i> Todos los Productos</a>
                    </li>
                </ul>
            </li>
            <li class="start {{ isset($menu) && $menu == 'marketing' ? 'active open' : '' }}">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Marketing</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{ isset($submenu) && $submenu == 'new_slider' ? 'active' : '' }}">
                        <a href="/admin/marketing/sliders/create"><i class="fa fa-user"></i> Nueva Presentación</a>
                    </li>
                    <li class="{{ isset($submenu) && $submenu == 'sliders' ? 'active' : '' }}">
                        <a href="/admin/marketing/sliders"><i class="fa fa-user"></i> Presentaciones</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->