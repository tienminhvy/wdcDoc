<?php 
    session_start();
    define('isSet', 1);
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
    require('db_connect.php');
?>

<?php 
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $operationType = $_GET['request'];
    $typeRequest = $_GET['type'];
    $idToEdit = $_GET['id'];
    // Form Date()
    $fdate = date('d');
    $fmonth = date('m');
    function selectMonth($m){
        return "$('#wdc_emonth [value=$m]').attr('selected','selected');";
    }
    $fyear = date('Y');
    $fhour = date('H');
    $fminute = date('i');
    $fsecond = date('s');

    $fayear = $_POST['year'];
    $famonth = $_POST['month'];
    $fadate = $_POST['date'];
    $fahour = $_POST['hour'];
    $faminute = $_POST['minute'];
    $fasecond = $_POST['second'];

    if (isset($fayear)&&isset($famonth)&&isset($fadate)&&isset($fahour)&&isset($faminute)&&isset($fasecond)) {
        $fullDate = "$fayear-$famonth-$fadate $fahour:$faminute:$fasecond";
    } else {
        $fullDate = null;
    }
    $dom = new DOMDocument();
    // Process the user content:
    if (isset($_POST['title'])) { // Nếu title đã được nhập
        $title = $_POST['title']; // title
        if (isset($_POST['content'])) {
            // if (isset)
            $content = $_POST['content']; // content
            @$dom->loadHTML($content); // load dom
            $script = $dom->getElementsByTagName('script'); // lấy tag script
            $remove = []; // tạo arr $remove
            foreach($script as $item){$remove[] = $item;} // lưu từng tag lấy được vào remove
            foreach ($remove as $item){$item->parentNode->removeChild($item);} // lấy từng tag trong remove xoá child
            $content = $dom->saveHTML(); // lưu
            if (isset($fullDate)) {
                $db->insertTable($typeRequest, 'title, content, author, date', $title, $content, (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'], $fullDate);
                $idFromDbToEdit = mysqli_fetch_assoc($db->selectCol($typeRequest, "MAX(id)"));
                header("Location: operation.php?request=edit&type=posts&id=".$idFromDbToEdit['MAX(id)'], TRUE, 303);
            } else {
                $error = 'You must fill out which time to create this!';
            }
        } else {
            $error = 'You must fill out the content!';
        }
    } else {
        $error = 'You must fill out the title!';
    }
    
    
?>

<?php require_once(__DIR__.'/themes/default/header.php'); ?>

<?php require_once(__DIR__.'/themes/default/modules/mainMenus.php') ?>

<?php 
$createOn = 
"<h5>When</h5>
<input type='number' name='date' id='wdc_edate' min='0' max='31' value='$fdate'>
<select name='month' id='wdc_emonth'>
    <option value='01'>01 - Jan</option>
    <option value='02'>02 - Feb</option>
    <option value='03'>03 - Mar</option>
    <option value='04'>04 - Apr</option>
    <option value='05'>05 - May</option>
    <option value='06'>06 - Jun</option>
    <option value='07'>07 - Jul</option>
    <option value='08'>08 - Aug</option>
    <option value='09'>09 - Sep</option>
    <option value='10'>10 - Oct</option>
    <option value='11'>11 - Nov</option>
    <option value='12'>12 - Dec</option>
</select>
<input type='number' name='year' id='wdc_eyear' min='0' max='9999' value='$fyear'>
<br />
<span>at </span><input type='number' name='hour' id='wdc_ehour' min='0' max='23' value='$fhour'><span>:</span><input type='number' name='minute' id='wdc_emin' min='0' max='59' value='$fminute'><span>:</span><input type='number' name='second' id='wdc_esec' min='0' max='59' value='$fsecond'>";
    $css = 
"<style>
.$operationType.$typeRequest > a {
    background: black;
    color: rgb(231, 231, 231);
}
#wdc_admin_create > a{
    background: black;
}
.wdc_submenu_01 {
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
.wdc_submenu_01_collapsed {
    left: 45px !important;
    opacity: 0;
}
</style>";
    $js = '<script>'.selectMonth($fmonth);
    if ($operationType == 'edit') {
        switch ($typeRequest) {
            case 'posts':
                $js .= "$.post('url.php', {type: 'post', id: $idToEdit},function (data,status) { $('#permalink').html(data);});";
                break;

            case 'pages':
                $js .= "$.post('url.php', {type: 'page', id: $idToEdit},function (data,status) { $('#permalink').html(data);});";
                break;

            case 'categories':
                $js .= "$.post('url.php', {type: 'category', id: $idToEdit},function (data,status) { $('#permalink').html(data);});";
                break;
            
            default:
                break;
        }
    }
    $js .= "let collapseCol = false;
    $('#wdc_admin_create > ul').addClass('wdc_submenu_01');
    $('#wdc_collapseActivate').on('click', function (){
        switch (collapseCol) {
            case false:
            $('#wdc_admin_create > ul').removeClass('wdc_submenu_01');
            $('#wdc_admin_create > ul').addClass('wdc_submenu_01_collapsed');
            collapseCol = true;
            break;

            default:
            $('#wdc_admin_create > ul').addClass('wdc_submenu_01');
            $('#wdc_admin_create > ul').removeClass('wdc_submenu_01_collapsed');
            collapseCol = false;
        break;
        }
    });";
    $js .= '</script>';
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


$htmlViewPosts =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
            <table class='table'>
                <thead>
                    <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>First</th>
                        <th scope='col'>Last</th>
                        <th scope='col'>Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope='row'>1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope='row'>2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <th scope='row'>3</th>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>";
$htmlViewPages =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                test
            </col>
        </div>
    </div>
</main>";
$htmlViewCategories =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                test
            </col>
        </div>
    </div>
</main>";


$htmlEditPost =
"<main>
<form method='POST'>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2>Edit post</h2>
                <input type='text' class='form-control' name='title'>
                <span>URL: </span><span id='permalink'></span>
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
$htmlEditPage =
"<main>
<form method='POST'>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2>Edit post</h2>
                <input type='text' class='form-control' name='title'>
                <span>URL: </span><span id='permalink'></span>
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
$htmlEditCategory =
"<main>
<form method='POST'>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2>Edit post</h2>
                <input type='text' class='form-control' name='title'>
                <span>URL: </span><span id='permalink'></span>
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

    switch ($operationType) {
        case 'create':
            switch ($typeRequest) {
                case 'posts':
                    echo $htmlCreatePost;
                    break;
                
                case 'pages':
                    echo $htmlCreatePage;
                    break;
                case 'categories';
                    echo $htmlCreateCategory;
                    break;
                default:
                echo $invalidRequest;
                break;
            }
            break;
        
        case 'view':
            switch ($typeRequest) {
                case 'posts':
                    echo $htmlViewPosts;
                    break;
                
                case 'pages':
                    echo $htmlViewPages;
                    break;
                case 'categories';
                    echo $htmlViewCategories;
                    break;
                default:
                echo $invalidRequest;
                break;
            }
            break;

        case 'edit':
            switch ($typeRequest) {
                case 'posts':
                    echo $htmlEditPost;
                    break;
                
                case 'pages':
                    echo $htmlEditPage;
                    break;
                case 'categories';
                    echo $htmlEditCategory;
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

<?php require_once(__DIR__.'/themes/default/footer.php');echo $css.$js?>