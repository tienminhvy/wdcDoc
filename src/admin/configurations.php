<?php 
    session_start();
    define('isSet', 1);
    define('setting', 1);
    require_once('settings.php');
    require_once('db_connect.php');
    require_once('../loginCheck.php');
    require_once('../validate.php');
    if (!isset($installed)) {
        die("You must run the installation file (install.php) in the admin directory in order to run this file.");
    }
    // Kiểm tra đăng nhập
    loginCheck($wdc_id,$wdc_token,$db,true);
    require_once('services/categoryConvert.php');
?>

<?php 
    $siteURLF = $_POST['siteURL'];
    $siteNameF = $_POST['siteName'];
    $siteEmailF = $_POST['siteEmail'];

    $oldValue[] = $site_addr;
    $oldValue[] = $sitename;
    $oldValue[] = $site_email;

    $site_addrF = $site_addr;
    $sitenameF = $sitename;
    $site_emailF = $site_email;

    $checking = new userChecking('', $siteEmailF, '', $siteURLF);

    $newValue[] = $siteURLF;
    $newValue[] = $siteNameF;
    $newValue[] = $siteEmailF;

    if (isset($siteURLF) && isset($siteNameF) && isset($siteEmailF)) {
        if ($checking->checkEmail() && $checking->checkURL() && $siteNameF!='') {
            $settingsFile = file('settings.php');
            $settingsFileFopen = fopen('settings.php', 'w');
            $count = 0;
            foreach ($settingsFile as $value) {
                if (strpos($value, $oldValue[$count])!=false) { // nếu tìm thấy chuỗi
                    $value = str_replace("'$oldValue[$count]'", "'$newValue[$count]'", $value);
                    $settingsFileNew[] = $value;
                    ++$count;
                } else { // nếu ko
                    $settingsFileNew[] = $value;
                }
            }
            $content = implode("", $settingsFileNew);
            fwrite($settingsFileFopen, $content);
            fclose($settingsFileFopen);
        } elseif ($siteNameF=='') {
            $errName = '<b>Please enter site name</b>';
        }
        $site_addrF = $siteURLF;
        $sitenameF = $siteNameF;
        $site_emailF = $siteEmailF;
    }
?>

<?php 
    require_once('themes/default/header.php');
    require_once('themes/default/modules/mainMenus.php');
?>
<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="text-center">Configuration</h1>
                <form method="post">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="siteURL">Site URL:</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Site URL" aria-label="Site URL" aria-describedby="siteURL" name="siteURL" value="<?php echo $site_addrF ?>">
                    </div>
                    <?php echo $errURL ?>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="siteName">Site name:</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Site name" aria-label="Site URL" aria-describedby="siteName" name="siteName" value="<?php echo $sitenameF ?>">
                    </div>
                    <?php echo $errName ?>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="siteEmail">Site email:</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Site email" aria-label="Site URL" aria-describedby="siteEmail" name="siteEmail" value="<?php echo $site_emailF ?>">
                    </div>
                    <?php echo $errEmail ?>
                    <button class="btn btn-info btn-block">Save</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php
    require_once('themes/default/footer.php');
?>