<?php 
    define('isSet', 1);
    define('setting',1);
    require_once(__DIR__.'/settings.php');
?>
<?php require_once(__DIR__.'/themes/default/header.php'); ?>

<?php require_once(__DIR__.'/themes/default/modules/mainMenus.php') ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col notify">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Welcome to your Docs Dashboard</h5>
                        <div class="container">
                            <div class="row">
                                <div class="col-4">
                                    <h6>Statistics</h6>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Total Posts</th>
                                                <td>Number</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Total Page</th>
                                                <td>Number</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Total User</th>
                                                <td>Number</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col"></div>
                                <div class="col-7 wdc_admin_link">
                                    <h6>Control your website</h6>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-6">
                                                <p><a href="<?php echo $site_addr.'/admin/create.php?request=create&type=page'?>"><i class="fi-cwsuxl-plus-solid"></i><span>Add a page</span></a></p>
                                                <p><a href="<?php echo $site_addr.'/admin/create.php?request=create&type=post'?>"><i class="fi-swsuxl-pen"></i><span>Add a post</span></a></p>
                                                <p><a href=""><i class="fi-cnsuxl-gavel"></i><span>Moderate comment section</span></a></p>
                                                <p><a href=""><i class="fi-cnsuxl-question-mark"></i><span>Read Manual</span></a></p>
                                            </div>
                                            <div class="col-6"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Note for all users who can access this dashboard</h5>
                        <textarea name="note" id="admin_note" style="width: 100%" rows="10"></textarea>
                        <button style="width: 100%">Save</button>
                    </div>
                </div>
            </div>
            <div class="col"></div>
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Comments</h5>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once(__DIR__.'/themes/default/footer.php'); ?>