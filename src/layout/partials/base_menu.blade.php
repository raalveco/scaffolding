<!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
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
                <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                <li class="sidebar-search-wrapper">
                    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                    <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                    <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                    <form class="sidebar-search " action="extra_search.html" method="POST">
                        <a href="javascript:;" class="remove">
                        <i class="icon-close"></i>
                        </a>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                            <a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
                            </span>
                        </div>
                    </form>
                    <!-- END RESPONSIVE QUICK SEARCH FORM -->
                </li>
                <li class="start ">
                    <a href="javascript:;">
                    <i class="icon-home"></i>
                    <span class="title">Submenu</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="index.html">
                            <i class="icon-bar-chart"></i>
                            Option 1</a>
                        </li>
                        <li>
                            <a href="index_2.html">
                            <i class="icon-bulb"></i>
                            Option 2</a>
                        </li>
                        <li>
                            <a href="index_3.html">
                            <i class="icon-graph"></i>
                            Option 3</a>
                        </li>
                    </ul>
                </li>
                <!-- BEGIN ANGULARJS LINK -->
                <li class="tooltips" data-container="body" data-placement="right" data-html="true" data-original-title="AngularJS version demo">
                    <a href="angularjs" target="_blank">
                    <i class="icon-paper-plane"></i>
                    <span class="title">
                    Menu </span>
                    </a>
                </li>
                <!-- END ANGULARJS LINK -->
                <li class="heading">
                    <h3 class="uppercase">Separator</h3>
                </li>
                <li>
                    <a href="javascript:;">
                    <i class="icon-folder"></i>
                    <span class="title">Multi Level Menu</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="javascript:;">
                            <i class="icon-settings"></i> Item 1 <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                    <i class="icon-user"></i>
                                    Sample Link 1 <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#"><i class="icon-power"></i> Sample Link 1</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon-paper-plane"></i> Sample Link 1</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon-star"></i> Sample Link 1</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-camera"></i> Sample Link 1</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-link"></i> Sample Link 2</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-pointer"></i> Sample Link 3</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                            <i class="icon-globe"></i> Item 2 <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="#"><i class="icon-tag"></i> Sample Link 1</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-pencil"></i> Sample Link 1</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-graph"></i> Sample Link 1</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                            <i class="icon-bar-chart"></i>
                            Item 3 </a>
                        </li>
                    </ul>
                </li>
                <li class="last ">
                    <a href="javascript:;">
                    <i class="icon-pointer"></i>
                    <span class="title">Last</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="">
                            Option 1</a>
                        </li>
                        <li>
                            <a href="">
                            Option 2</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->