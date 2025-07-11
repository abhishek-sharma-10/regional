    <nav class="navbar-default navbar-static-side " role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element" aligtn="center"> 
                        <span>
                            <img alt="image" class="img-cicle" src="<?php echo base_url();?>public/assets/img/logo1.png"  height="90"/>
                        </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> 
                                <span class="block m-t-xs"> <strong class="font-bold"></strong> </span>
                                <span class="text-muted text-xs block"><?php echo $_SESSION['user'][0]->name ?><b class="caret"></b></span>
                            </span> 
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="<?php echo base_url();?>admin/home/profile">Profile</a></li>
                            <li><a href="<?php echo base_url();?>admin/home/reset-password">Reset Password</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo base_url();?>admin/logout">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        RIE
                    </div>
                </li>
                <?php
                function menuActive($pages)
                {
                    $pageName = basename($_SERVER['PHP_SELF']);
                    #if ($pageName == trim($page)) {
                    if (in_array($pageName, $pages)) {
                        return 'active';
                    }
                }
                function subMenuActive($pages, $module)
                {
                    //session_start();
                    $pageName = basename($_SERVER['PHP_SELF']);
                    if (in_array($pageName, $pages)) {
                        return 'active';
                    }
                }

                // if (!empty($_SESSION['user'])) {
                ?>
                <!-- <li class="<?= menuActive(array('h', 'home')) ?>">
                        <a href="<?php echo base_url();?>admin/home"><i class="fa fa-home "></i> <span class="nav-label">Home</span></a>
                    </li>
                    
                    <li class="<?= menuActive(array('registrations')) ?>">
                        <a href="<?php echo base_url();?>admin/registrations"><i class="fa fa-file"></i> <span class="nav-label">Registrations</span></a>
                    </li>
                <li class="<?= menuActive(array('ncet-applications')) ?>">
                    <a href="<?php echo base_url(); ?>admin/ncet-applications"><i class="fa fa-file"></i> <span class="nav-label">NCET Applications</span></a>
                </li> -->

                <!-- <li class="<?= menuActive(array('counselling')) ?>">
                        <a href="<?php echo base_url(); ?>admin/counselling"><i class="fa fa-file"></i> <span class="nav-label">Counselling</span></a>
                    </li> -->

                <!-- <li class="<?= subMenuActive(array('add', 'show'), 'Counselling') ?>">
                    <a><i class="fa fa-users"></i> <span class="nav-label">Counselling</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'add' ? 'active' : '';  ?>"><a href="<?php echo base_url('admin/counselling/add'); ?>">Add Counselling</a></li>
                        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'show' ? 'active' : '';  ?>"><a href="<?php echo base_url('admin/counselling/show'); ?>">Show Counselling</a></li>
                    </ul>
                </li>

                <li class="<?= subMenuActive(array('state-wise-report', 'category-wise-report', 'course-wise-report'), 'Reports') ?>">
                        <a><i class="fa fa-users"></i> <span class="nav-label">Reports</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'category-wise-report' ? 'active' : '';  ?>"><a href="<?php echo base_url('admin/report/category-wise-report'); ?>">Course-wise Report</a></li>
                            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'state-wise-report' ? 'active' : '';  ?>"><a href="<?php echo base_url('admin/report/state-wise-report'); ?>">State-wise Report</a></li>
                        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'course-wise-report' ? 'active' : '';  ?>"><a href="<?php echo base_url('admin/report/course-wise-report'); ?>">Subject-wise Report</a></li>
                        </ul>
                </li> -->
                <?php
                // }
                ?>
                <?php if (!empty($navbar)) {
                    foreach ($navbar as $menu) {   ?>
                        <li id="<?php echo $menu->routes_name ?>-tab" class="parent-menu">
                            <a href="<?php echo base_url('admin'.$menu->url) ?>"><i class="fa <?php echo $menu->icon ?>"></i> <span class="nav-label"><?php echo $menu->title ?></span>
                            <?php
                                echo ($menu->has_submenu ?  '<span class="fa arrow"></span> </a>' : '</a>');
                                if ($menu->has_submenu) { ?>
                                    <ul class="nav nav-second-level collapse">
                                        <?php foreach ($menu->submenus as $submenu) {   ?>
                                            <li><a href="<?php echo base_url('admin'.$submenu->url) ?>"><?php echo $submenu->title ?></a></li>
                                        <?php } ?>
                                    </ul>
                            <?php } ?>
                        </li>
                <?php }
                } ?>
            </ul>
        </div>
    </nav>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" style="margin-right:15px;"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                        <div class="form-group row">
                            
                        </div>
                    </form> 
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="<?php echo base_url();?>admin/logout">
                            <span class="fa fa-sign-out"></span> Log out
                        </a>
                    </li>
                </ul>
            </nav>
        </div>