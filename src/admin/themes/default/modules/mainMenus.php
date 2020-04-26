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
                <a href="" class="wdc_activateSmenu1">Welcome, user!</a>
                <ul class="wdc_ad_smenu_1">
                    <li><a href="">Edit profile</a></li>
                    <li><a href="">Log out</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <nav id="wdc_ad_nav2">
        <ul>
            <li id="wdc_admin_home"><a href="<?php echo $site_addr.'/admin'?>"><i class="fi-snsuxl-signal-solid"></i><span>Overview</span></a></li>
            <li id="wdc_admin_comments"><a href=""><i class="fi-stsuxl-comment-dots-thin"></i><span>Comments</span></a></li>
            <li id="wdc_admin_create">
                <a href="<?php echo $site_addr.'/admin/create.php?request=create&type=post' ?>"><i class="fi-xnsuxl-edit-solid"></i><span>Pages / Posts</span></a>
                <ul>
                    <li class="create post"><a href="<?php echo $site_addr.'/admin/create.php?request=create&type=post'?>">Create post</a></li>
                    <li class="view post"><a href="<?php echo $site_addr.'/admin/create.php?request=view&type=post' ?>">View posts</a></li>
                    <li class="create page"><a href="<?php echo $site_addr.'/admin/create.php?request=create&type=page' ?>">Create page</a></li>
                    <li class="view page"><a href="<?php echo $site_addr.'/admin/create.php?request=view&type=page' ?>">View pages</a></li>
                    <li class="create category"><a href="<?php echo $site_addr.'/admin/create.php?request=create&type=category' ?>">Create category</a></li>
                    <li class="view category"><a href="<?php echo $site_addr.'/admin/create.php?request=view&type=category' ?>">View categories</a></li>
                </ul>
            </li>
            <li id="wdc_admin_media"><a href="" ><i class="fi-xnsuxl-upload-solid"></i><span>Media</span></a></li>
            <li id="wdc_admin_theme"><a href="" ><i class="fi-xnsuxl-bucket-drip-solid"></i><span>Theme</span></a></li>
            <li><a href="javascript:void(0)" id="wdc_collapseActivate"><i id="wdc_collapseActivateIcon" class="fi-cnslxl-chevron-solid" class="collapseIcon"></i><span>Collapse menu</span></a></li>
        </ul>
    </nav>
</header>