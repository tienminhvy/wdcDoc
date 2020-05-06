<?php 
    session_start();
    define('isSet', 1);
    define('setting', 1);
    require_once('settings.php');
    require_once('db_connect.php');
    require_once('../loginCheck.php');
    if (!isset($installed)) {
        die("You must run the installation file (install.php) in the admin directory in order to run this file.");
    }
    // Kiểm tra đăng nhập
    loginCheck($wdc_id,$wdc_token,$db,true);
    require_once('services/categoryConvert.php');
?>

<?php 
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $typeRequest = $_GET['type'];
    $issubmit = $_GET['issubmit'];
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
    // Xử lý nội dung của user:
    function errorTemplate($error)
    {
        if (isset($error)) {
            return "<div class='alert alert-danger' role='alert'>".$error."</div>";
        }
    }
    function successTemplate($success)
    {
        if (isset($success)) {
            return "<div class='alert alert-success' role='alert'>".$success."</div>";
        }
    }
    if ($issubmit == 'yes') { // nếu user nhấn create
        if (isset($_COOKIE['wdc_id'])) { // kt cookie
            $userId = $_COOKIE['wdc_id'];
        } else {
            $userId = $_SESSION['wdc_id'];
        }
        // lấy dữ liệ
        $resultfdb = mysqli_fetch_assoc($db->selectValue('users', "wdc_id='$userId'", 'username'));
        $author = $resultfdb['username'];
        if (!$_POST['title']=='') { // Nếu title đã được nhập
            $title = $_POST['title']; // title
            if (!$_POST['content']=='') {
                // if (isset)
                $content = $_POST['content']; // content
                if (isset($fullDate)) {
                    switch ($typeRequest) {
                        case 'post':
                            // Gửi dữ liệu bài viết đến CSDL
                            if (isset($_POST['category'])) { // nếu đã chọn
                                $categoryId = $_POST['category']; // id của category
                                $categoryResult = mysqli_fetch_assoc($db->selectValue('categories', "id = '$categoryId'", 'slug')); // lấy kết quả từ db
                                $category = $categoryResult['slug']; // category là category slug
                            } else { // nếu ko
                                $category = 'uncategorized';
                            }
                            $db->insertTable('posts', 'title, content, author, category, date', $title, $content, $author, $category,$fullDate);
                            break;
                        
                        case 'page':
                            $db->insertTable('pages', 'title, content, author, date', $title, $content, $author, $fullDate);
                            break;

                        case 'category':
                            $slug = convert_category($title);
                            $db->insertTable('categories', 'title, slug, content, author, date', $title, $slug, $content, $author, $fullDate);
                            break;
                    }
                    // Lấy giá trị mới đưa vào
                    switch ($typeRequest) {
                        case 'post':
                            $idFromDbToEdit = mysqli_fetch_assoc($db->selectCol('posts', "MAX(id)"));
                            header("Location: edit.php?rdfrom=create&type=post&id=".$idFromDbToEdit['MAX(id)'], TRUE, 303);
                            break;
                        case 'page':
                            $idFromDbToEdit = mysqli_fetch_assoc($db->selectCol('pages', "MAX(id)"));
                            header("Location: edit.php?rdfrom=create&type=page&id=".$idFromDbToEdit['MAX(id)'], TRUE, 303);
                            break;
                        case 'category':
                            $idFromDbToEdit = mysqli_fetch_assoc($db->selectCol('categories', "MAX(id)"));
                            header("Location: edit.php?rdfrom=create&type=category&id=".$idFromDbToEdit['MAX(id)'], TRUE, 303);
                            break;
                    }
                } else {
                    $error = 'You must fill out which time to create this!';
                }
            } else {
                $error = 'You must fill out the content!';
            }
        } else {
            $error = 'You must fill out the title!';
        }
    }
    $formAction = "create.php?type=$typeRequest&issubmit=yes";
?>

<?php require_once(__DIR__.'/themes/default/header.php'); ?>

<?php require_once(__DIR__.'/themes/default/modules/mainMenus.php') ?>

<?php 

$categories = $db->selectCol('categories', 'id', 'title');
$categoriesResults = mysqli_fetch_all($categories);
$categoryOptions = "<input type='radio' name='category' value='1' id='category-1' checked='checked'> <label for='category-1'>Uncategorized</label> <br>";
for ($i=1; $i < count($categoriesResults); $i++) { 
    for ($j=0; $j < count($categoriesResults[$i]); $j++) { 
        if ($j==0){
            $val = $categoriesResults[$i][0];
        } else {
            $name = $categoriesResults[$i][$j];
            $categoryOptions .= "<input type='radio' name='category' value='$val' id='category-$val'> <label for='category-$val'>$name</label> <br>";
        }
    }
}


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
.create.$typeRequest > a {
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
        break;}
    });
    $(function () {
        tinymce.get('textarea').on('keyup', function(e) {
            content = tinymce.get('textarea').getContent();
            if (content != '') {
                window.onbeforeunload = function(e){
                    e.returnValue = 'Information entered will be lost forever!';
                };
            }
        });
    });
    ";
    $js .= '</script>';
    $invalidRequest =
"<main><h1>Invalid request, please try again!</h1><main>";
    $htmlCreatePost = 
"<main>
    <form method='POST' action='$formAction'>
        <div class='container'>
            <div class='row'>
                <div class='col'>
                    <h2>Add new post</h2>
                    ".successTemplate($success).errorTemplate($error)."
                    <input type='text' class='form-control' name='title' value=''>
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
                            $categoryOptions
                            <br>
                            <span><button id='create' class='btn btn-info' type='submit'>Create</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>";
$htmlCreatePage = 
"<main>
    <form method='POST' action='$formAction'>
        <div class='container'>
            <div class='row'>
                <div class='col'>
                    <h2>Add new page</h2>
                    ".successTemplate($success).errorTemplate($error)."
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
                            <br>
                            <span><button id='create' class='btn btn-info' type='submit'>Create</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>";
    $htmlCreateCategory = 
"<main>
    <form method='POST' action='$formAction'>
        <div class='container'>
            <div class='row'>
                <div class='col'>
                    <h2>Add new category</h2>
                    ".successTemplate($success).errorTemplate($error)."
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
                            <br>
                            <span><button id='create' class='btn btn-info' type='submit'>Create</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>";

    switch ($typeRequest) {
        case 'post':
            echo $htmlCreatePost;
            break;
        
        case 'page':
            echo $htmlCreatePage;
            break;
        case 'category';
            echo $htmlCreateCategory;
            break;
        default:
        echo $invalidRequest;
        break;
    }
?>

<?php require_once(__DIR__.'/themes/default/footer.php');echo $css.$js?>