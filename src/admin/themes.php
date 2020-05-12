<?php 
    session_start();
    define('isSet', 1);
    define('setting',1);
    require_once('settings.php');
    require_once('db_connect.php');
    if (!isset($installed)) {
        die("You must run the installation file (install.php) in this directory before continue!");
    }
    require_once('../loginCheck.php');
    loginCheck($wdc_id, $wdc_token, $db, true);
?>
<?php 
    // lấy dữ liệu từ url
    $isChangeTheme = $_GET['changeTheme'];
    $fileToOpen = $_GET['file'];
    // từ admin sang ổ themes
    chdir('../themes');
    // thu thập danh sách thư mục
    $dirs = glob('*', GLOB_ONLYDIR);
    foreach ($dirs as $value) {
        // các theme có trong themes
        $themeChangerOption .= 
        "<option value='$value'>$value</option>";
    }
    // nếu đổi theme thì
    if ($isChangeTheme==true) {
        if (isset($_POST['themeChanger'])){
            $selectedTheme = $_POST['themeChanger'];
        } else {
            $selectedTheme = $dirs[0];
        }
    } else {
        $selectedTheme = $dirs[0];
    }
    chdir($selectedTheme).'<br>';
    define('ABSOLUTEPATH', getcwd());
    chdir('..');
    function getList($dirName, $index = 0)
    {
        if ($index>0) {
            $prefix .= str_repeat('-', $index);
        } else {
            $prefix = '';
        }
        chdir("$dirName"); // chuyển ổ sang ổ theme
        $GLOBALS['getList'] .= "Dir: $prefix $dirName";
        $files = glob('*.*'); // lấy tất cả file
        foreach ($files as $value) {
            $absolutePath = getcwd().'\\'; // địa chỉ tuyệt đối
            $relativePath = substr(str_replace(ABSOLUTEPATH,'',$absolutePath), 1); // tương đối
            $GLOBALS['getList'] .= "<div class='card-body'>
                    <div class='list-group'>";
                                                                                                                // mã hoá url
            $GLOBALS['getList'] .= "<a class='list-group-item list-group-item-action' href='themes.php?file=".urlencode("$relativePath$value")."'>$value</a>";
            $GLOBALS['getList'] .= "</div></div>";
        }
        ++$index;
        $dirs = glob('*', GLOB_ONLYDIR); // lấy tất cả directory
        foreach ($dirs as $value) { // lặp
            getList($value, $index);
            chdir('..'); // sau khi đệ quy, quay về ổ đĩa trước
        }
    }
    getList($selectedTheme);
    if (isset($fileToOpen)){
        $fileForProcess = fopen("$fileToOpen",'r');
        @$fileOpended = fread($fileForProcess, filesize("$fileToOpen"));
        fclose($fileForProcess);
    } else {
        $fileForProcess = fopen("info.php",'r');
        @$fileOpended = fread($fileForProcess, filesize("info.php"));
        fclose($fileForProcess);
    }
    $valFromUser = $_POST['codemirrorValueFromUser'];
    echo $valFromUser;
    $fileIsEdited = false;
    if (isset($valFromUser) && isset($fileToOpen)) {
        $fileIsEdited = true;
        $fileForProcess = fopen("$fileToOpen",'w+');
        fwrite($fileForProcess, "$valFromUser");
        fclose($fileForProcess);
        $success = "Edit file successfully";
    } elseif (isset($valFromUser)) {
        $fileIsEdited = true;
        $fileForProcess = fopen("info.php",'w+');
        fwrite($fileForProcess, "$valFromUser");
        fclose($fileForProcess);
        $success = "Edit file successfully";
    }
    if ($fileIsEdited) {
        $fileOpended = $valFromUser;
    }
    $file_parts = pathinfo("$fileToOpen");

    switch($file_parts['extension'])
    {
        case "css":
            $codemirrorMode = 'text/css';
        break;

        case "js":
            $codemirrorMode = 'text/javascript';
        break;

        default:
            $codemirrorMode = 'application/x-httpd-php';
        break;
    }
    if (isset($fileToOpen)) {
        $checkToActive = "$fileToOpen";
    } else {
        $fileToOpen = 'info.php';
    }
    
?>
<?php 
    function successTemplate($success)
    {
        if (isset($success)) {
            return "<div class='alert alert-success' role='alert'>$success</div>";
        }
        return;
    }
    $css =
    "<style>
    #codemirror {
        height: auto;
    }
    #textareaCodemirror {
        display: none;
    }
    </style>";
    $js =
    "<script>
    $(function () {
        codemirror = document.getElementById('codemirror');
        var myCodeMirror = CodeMirror(codemirror, {
            value: `$fileOpended`,
            lineNumbers: true,
            mode:  '$codemirrorMode',
            extraKeys: {'Ctrl-Space': 'autocomplete'},
            theme: 'monokai',
        });  
        myCodeMirror.setSize('100%', 'auto');
        $('#fileSubmitBtn').on('click', function() {
            $('#textareaCodemirror').html(myCodeMirror.getValue());
            setTimeout(function(){ $('#editFileForm').submit(); }, 100);
        });
        $(`#themeChanger [value='$selectedTheme']`).attr('selected', 'selected');
        if (myCodeMirror.getValue()!=='') {
            window.onbeforeunload = function(event) {
                event.returnValue = 'Exiting..';
            };
        }
    });
    </script>";
    $themeChanger = 
    "<select name='themeChanger' class='form-control' id='themeChanger'>$themeChangerOption</select>";
    $codemirrorPHP =
    "<script src='services/codemirror/lib/codemirror.js'></script>
    <link rel='stylesheet' href='services/codemirror/lib/codemirror.css'>
    <script src='services/codemirror/mode/php/php.js'></script>
    <script src='services/codemirror/addon/hint/show-hint.js'></script>
    <link rel='stylesheet' href='services/codemirror/addon/hint/show-hint.css'>
    <script src='services/codemirror/mode/clike/clike.js'></script>
    <script src='services/codemirror/mode/htmlmixed/htmlmixed.js'></script>
    <script src='services/codemirror/mode/css/css.js'></script>
    <script src='services/codemirror/mode/css/css.js'></script>
    <script src='services/codemirror/mode/xml/xml.js'></script>
    <script src='services/codemirror/mode/javascript/javascript.js'></script>
    <link rel='stylesheet' href='services/codemirror/theme/monokai.css'>";
    require_once('themes/default/header.php');
    echo $css;
    require_once('themes/default/modules/mainMenus.php');
    echo $codemirrorPHP;
    echo "<main>
        <div class='container'>
            <div class='row'>
                <div class='col'><h2 class='text-center'>Edit theme</h2></div>
            </div>
            <div class='row'>
                <div class='col-8'>
                    <div class='alert alert-warning' role='alert'>
                        Please double-check the edited file for error
                        before saving the content. This software currently do not support the 
                        PHP Complier features.
                    </div>
                    ".successTemplate($success)."
                    <form method='POST' id='editFileForm'>
                        <div id='codemirror'></div>
                        <textarea id='textareaCodemirror' name='codemirrorValueFromUser'></textarea>
                        <div id='fileSubmitBtn' class='btn btn-primary'>Save</div>
                    </form>
                </div>
                <div class='col-4'>
                    <form method='POST' action='themes.php?changeTheme=true'>
                        <h5>Change theme</h5>
                        $themeChanger
                        <button class='btn btn-info btn-block'>Submit</button>
                    </form>
                    $getList
                </div>
            </div>
        </div>
    </main>";
    require_once('themes/default/footer.php');
    echo $js;
?>