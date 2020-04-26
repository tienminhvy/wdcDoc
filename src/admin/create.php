<?php 
    session_start();
    define('setting', 1);
    require('settings.php');
    if (!isset($installed)) {
        die("You must run the installation file (install.php) in the admin directory in order to run this file.");
    }
    if (!$_SESSION['logged']) { // nếu chưa đăng nhập thì chuyển hướng đến trang đăng nhập
        if (!$_COOKIE['logged']) {
            header("Location: $site_addr/login.php",TRUE,303);
            die('Not logged');
        }
    }
?>

<?php 
    // Process the user content:
    if (isset($_POST['title'])) {
        $title = $_POST['title'];
        if (isset($_POST['content'])) {
            // if (isset)
            $content = $_POST['content'];
        } else {
            $error = 'You must fill out the content';
        }
    } else {
        $error = 'You must fill out the title';
    }
    // $date = $_POST['title'];
?>

<?php 
    define('isSet', 1);
?>
<?php require_once(__DIR__.'/themes/default/header.php'); ?>

<?php require_once(__DIR__.'/themes/default/modules/mainMenus.php') ?>

<?php 
    $viewOrCreate = $_GET['request'];
    $typeRequest = $_GET['type'];
$createOn = 
"<h5>When</h5>
<input type='number' name='date' id='wdc_edate' min='0' max='31'>
<select name='month' id='wdc_emonth'>
    <option value='1'>01 - Jan</option>
    <option value='2'>02 - Feb</option>
    <option value='3'>03 - Mar</option>
    <option value='4'>04 - Apr</option>
    <option value='5'>05 - May</option>
    <option value='6'>06 - Jun</option>
    <option value='7'>07 - Jul</option>
    <option value='8'>08 - Aug</option>
    <option value='9'>09 - Sep</option>
    <option value='10'>10 - Oct</option>
    <option value='11'>11 - Nov</option>
    <option value='12'>12 - Dec</option>
</select>
<input type='number' name='year' id='wdc_eyear' min='0' max='9999'>
<br />
<span>at </span><input type='number' name='hour' id='wdc_ehour' min='0' max='23'><span>:</span><input type='number' name='minute' id='wdc_emin' min='0' max='59'><span>:</span><input type='number' name='second' id='wdc_esec' min='0' max='59'>";
    $css = 
"<style>
.$viewOrCreate.$typeRequest > a {
    background: black;
    color: rgb(231, 231, 231);
}
#wdc_admin_create > a{
    background: black;
}
#wdc_admin_create > ul {
    position: static !important;
    top: 0 !important;
    z-index: 1 !important;
    background: #363636 !important;
    width: 200px !important;
    left: 0 !important;
    opacity: 1 !important;
    visibility: visible !important;
    transition: unset !important;
}
</style>";
    $invalidRequest =
"<main><h1>Invalid request, please try again!</h1><main>";
    $htmlCreatePost = 
"<main>
    <form method='POST'>
        <div class='container'>
            <div class='row'>
                <div class='col'>
                    <h2>Add new post</h2>
                    <input type='text' class='form-control' name='title'>
                    <input type='hidden' name='type' value='post'>
                    <span>URL: </span>
                </div>
            </div>
            <div class='row'>
                <div class='col-8'>
                    <textarea id='textarea' name='content'></textarea>
                </div>
                <div class='col-4'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Configuration</h5>
                            $createOn
                            <h5>Category</h5>
                            <div class='form-group'>
                                <select multiple class='form-control' name='' id=''>
                                    <option>Test1</option>
                                    <option>Test1</option>
                                    <option>Test1</option>
                                </select>
                            </div>
                            <span><button id='create' class='btn btn-info'>Create</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>";
$htmlCreatePage = 
"<main>
    <form method='POST'>
        <div class='container'>
            <div class='row'>
                <div class='col'>
                    <h2>Add new page</h2>
                    <input type='text' class='form-control' name='title'>
                    <input type='hidden' name='type' value='page'>
                    <span>URL: </span>
                </div>
            </div>
            <div class='row'>
                <div class='col-8'>
                    <textarea id='textarea' name='content'></textarea>
                </div>
                <div class='col-4'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Configuration</h5>
                            $createOn
                            <span><button id='create' class='btn btn-info'>Create</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>";
    $htmlCreateCategory = 
"<main>
    <form method='POST'>
        <div class='container'>
            <div class='row'>
                <div class='col'>
                    <h2>Add new category</h2>
                    <input type='text' class='form-control' name='title'>
                    <input type='hidden' name='type' value='category'>
                    <span>URL: </span>
                </div>
            </div>
            <div class='row'>
                <div class='col-8'>
                    <textarea id='textarea' name='content'></textarea>
                </div>
                <div class='col-4'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>Configuration</h5>
                            $createOn
                            <span><button id='create' class='btn btn-info'>Create</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>";
    switch ($viewOrCreate) {
        case 'create':
            switch ($typeRequest) {
                case 'post':
                    echo $css.$htmlCreatePost;
                    break;
                
                case 'page':
                    echo $css.$htmlCreatePage;
                    break;
                case 'category';
                    echo $css.$htmlCreateCategory;
                    break;
                default:
                echo $invalidRequest;
                break;
            }
            break;
        
        case 'view':
            switch ($typeRequest) {
                case 'post':
                    echo $css.$htmlViewPosts;
                    break;
                
                case 'page':
                    echo $css.$htmlViewPages;
                    break;
                case 'category';
                    echo $css.$htmlViewCategories;
                    break;
                default:
                echo $invalidRequest;
                break;
            }
            break;

        default:
        echo $invalidRequest;
        break;
    }
?>

<?php require_once(__DIR__.'/themes/default/footer.php');echo $css; ?>