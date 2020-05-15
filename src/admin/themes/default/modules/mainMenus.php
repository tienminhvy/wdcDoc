<?php 
    if(!defined('isSet')){
        die('<h1>Direct access is not allowed!</h1>');
    }
?>
<header>
    <nav id="wdc_ad_nav1">
        <ul>
            <li><span id="wdc_logo">wdcDoc</span></li>
        </ul>
        <ul class="wdc_ad_menu_1">
            <li>
                <a href="javascript:void(0)" class="wdc_activateSmenu1"><i class="fi-cnsuxl-question-mark"></i></a>
                <ul class="wdc_ad_smenu_1">
                    <li><a href="">About wdcDocs</a></li>
                    <li><a href="">About weDevCode</a></li>
                </ul>
            </li>
            <li>
                <a href="" class="wdc_activateSmenu1" id="adminInfo">Welcome, <?php echo $username ?></a>
                <ul class="wdc_ad_smenu_1">
                    <li><a href="">Edit profile</a></li>
                    <li><a href="<?php echo $site_addr.'/logout.php' ?>">Log out</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <nav id="wdc_ad_nav2">
        <ul>
            <li id="wdc_admin_home"><a href="<?php echo $site_addr.'/admin'?>"><i class="fi-snsuxl-signal-solid"></i><span>Overview</span></a></li>
            <li id="wdc_admin_comments"><a href="<?php echo $site_addr.'/underConstruction.php'?>"><i class="fi-stsuxl-comment-dots-thin"></i><span>Comments</span></a></li>
            <li id="wdc_admin_users">
                <a href="<?php echo $site_addr.'/admin/users.php'?>"><i class="fi-cnsuxl-user-circle-solid"></i><span>Users</span></a>
                <ul>
                    <li class="users main"><a href="<?php echo $site_addr.'/admin/users.php'?>">Manage users</a></li>
                    <li class="users add"><a href="<?php echo $site_addr.'/admin/users.php?type=add'?>">Add user</a></li>
                </ul>
            </li>
            <li id="wdc_admin_create">
                <a href="<?php echo $site_addr.'/admin/create.php?type=post' ?>"><i class="fi-xnsuxl-edit-solid"></i><span>Pages / Posts</span></a>
                <ul>
                    <li class="create post"><a href="<?php echo $site_addr.'/admin/create.php?type=post'?>">Create post</a></li>
                    <li class="view posts"><a href="<?php echo $site_addr.'/admin/view.php?type=posts' ?>">View posts</a></li>
                    <li class="create page"><a href="<?php echo $site_addr.'/admin/create.php?type=page' ?>">Create page</a></li>
                    <li class="view pages"><a href="<?php echo $site_addr.'/admin/view.php?type=pages' ?>">View pages</a></li>
                    <li class="create category"><a href="<?php echo $site_addr.'/admin/create.php?type=category' ?>">Create category</a></li>
                    <li class="view categories"><a href="<?php echo $site_addr.'/admin/view.php?type=categories' ?>">View categories</a></li>
                </ul>
            </li>
            <li id="wdc_admin_media"><a href="<?php echo $site_addr.'/underConstruction.php'?>" ><i class="fi-xnsuxl-upload-solid"></i><span>Media</span></a></li>
            <li id="wdc_admin_theme"><a href="<?php echo $site_addr.'/admin/themes.php'?>" ><i class="fi-xnsuxl-bucket-drip-solid"></i><span>Theme</span></a></li>
            <li id="wdc_admin_config">
                <a href="<?php echo $site_addr.'/admin/configurations.php'?>" ><i class="fi-xwluxl-gear-wide"></i><span>Setting</span></a>
            </li>
            <li><a href="javascript:void(0)" id="wdc_collapseActivate"><i id="wdc_collapseActivateIcon" class="fi-cnslxl-chevron-solid" class="collapseIcon"></i><span>Collapse menu</span></a></li>
        </ul>
    </nav>
</header>