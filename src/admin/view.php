<?php 
    session_start();
    define('setting', 1);
    define('isSet', 1);
    require_once('settings.php');
    require_once('db_connect.php');
    if (!isset($installed)) {
        die("You must run the installation file (install.php) in the admin directory in order to run this file.");
    }
    // Kiểm tra đăng nhập
    require_once('../loginCheck.php');
    loginCheck($wdc_id, $wdc_token, $db, true);
?>

<?php 
    require_once('themes/default/header.php')
?>

<?php 
    function pagination($pagination, $count, $total, $typeRequest) {
        if ($pagination>$count) {
            $GLOBALS['errorCheck'] = true;
            $GLOBALS['error'] = "Exceeded maximum count for $typeRequest, please try again!";
        }
        if ($total < 10) { // nếu số lượng bài dưới 10
            if ($pagination>1) { // nếu ko set pagination trên url
                $pagination = 1; // mặc định là 1
            }
        } elseif ($pagination==1 || $pagination=='') { // nếu đang ở trang view
            if ($count<4) { // kiểm tra nếu count < 4
                for ($i=1; $i <= $count; $i++) { 
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
            } else {
                for ($i=1; $i < 4; $i++) { 
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
                // trang cuối cùng
                $item .= 
                "<li class='page-item'>
                <a class='page-link' href='view.php?type=$typeRequest&pagination=$count' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
                <span class='sr-only'>Next</span>
                </a>
                </li>";
            }
            // lưu
            $GLOBALS['htmlPagination'] = 
            "<nav aria-label='Page navigation'>
            <ul class='pagination'>
            $item
            </ul>
            </nav>";
        } elseif ($pagination>1&&$pagination<4) { // nếu đang ở pagination là 2 và 3
            if ($pagination==$count) { // nếu số pagination trên url == count
                for ($i=1; $i <= $pagination; $i++) { 
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
            } elseif (($pagination+1)==$count||($pagination+2)==$count) {
                for ($i=1; $i <= $count; $i++) { 
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
            } else {
                for ($i=1; $i <= $pagination; $i++) { 
                    $item .=
                    "<li class='page-item' id='paginate-$i'><a class='page-link' href='view.php?type=$typeRequest&pagination=$i'>$i</a></li>";
                }
                $item .=
                "<li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>
                <li class='page-item' id='paginate-".($pagination+2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+2)."'>".($pagination+2)."</a></li>";
                $item .= 
                "<li class='page-item'>
                <a class='page-link' href='view.php?type=$typeRequest&pagination=$count' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
                <span class='sr-only'>Next</span>
                </a>
                </li>";
            }
            $GLOBALS['htmlPagination'] = 
            "<nav aria-label='Page navigation'>
            <ul class='pagination'>
            $item
            </ul>
            </nav>";
        } else {
            $item = 
            "<li class='page-item'>
            <a class='page-link' href='view.php?type=$typeRequest&pagination=1' aria-label='Previous'>
            <span aria-hidden='true'>&laquo;</span>
            <span class='sr-only'>Previous</span>
            </a>
            </li>";
            if ($pagination==$count) {
                $item .=
                "<li class='page-item' id='paginate-".($pagination-4)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-4)."'>".($pagination-4)."</a></li>
                <li class='page-item' id='paginate-".($pagination-3)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-3)."'>".($pagination-3)."</a></li>
                <li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-".($pagination)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination)."'>".($pagination)."</a></li>
                ";
            } elseif (($pagination+1)==$count) {
                $item .= 
                "<li class='page-item' id='paginate-".($pagination-3)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-3)."'>".($pagination-3)."</a></li>
                <li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-$pagination'><a class='page-link' href='view.php?type=$typeRequest&pagination=$pagination'>$pagination</a></li>
                <li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>";
            } elseif (($pagination+2)==$count) {
                $item .= 
                "<li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-$pagination'><a class='page-link' href='view.php?type=$typeRequest&pagination=$pagination'>$pagination</a></li>
                <li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>
                <li class='page-item' id='paginate-".($pagination+2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+2)."'>".($pagination+2)."</a></li>";
            } else {
                $item .= 
                "<li class='page-item' id='paginate-".($pagination-2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-2)."'>".($pagination-2)."</a></li>
                <li class='page-item' id='paginate-".($pagination-1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination-1)."'>".($pagination-1)."</a></li>
                <li class='page-item' id='paginate-$pagination'><a class='page-link' href='view.php?type=$typeRequest&pagination=$pagination'>$pagination</a></li>
                <li class='page-item' id='paginate-".($pagination+1)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+1)."'>".($pagination+1)."</a></li>
                <li class='page-item' id='paginate-".($pagination+2)."'><a class='page-link' href='view.php?type=$typeRequest&pagination=".($pagination+2)."'>".($pagination+2)."</a></li>";
                $item .=
                "<li class='page-item'>
                <a class='page-link' href='view.php?$total&pagination=$count' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
                <span class='sr-only'>Next</span>
                </a>
                </li>";
            }
            $GLOBALS['htmlPagination'] = 
            "<nav aria-label='Page navigation'>
            <ul class='pagination'>
            $item
            </ul>
            </nav>";
        }
    }
    $typeRequest = $_GET['type'];
    if (isset($_GET['pagination'])) {
        $pagination = $_GET['pagination'];
    } else {
        $pagination = 1;
    }
    switch ($typeRequest) {
        case 'posts':
            $getFDb = $db->selectCol('posts', 'id', 'title', 'author'); // lấy id, title, tác giả từ db
            $getCFDb = $db->selectCol('posts', 'COUNT(id) AS count'); // đếm số lượng
            $result = mysqli_fetch_all($getFDb);
            $resultC = mysqli_fetch_assoc($getCFDb);
            $total = $resultC['count'];
            $count = $resultC['count'];
            $count = ceil($count/10); // số trang pagination
            if ($resultC['count'] < 10) { // nếu số lượng bài dưới 10
                $post=1; // bài đầu tiên là 1
                for ($i=0; $i < $resultC['count']; $i++) {  // vòng lặp in bài
                    $template = ''; // reset biến template
                    for ($j=0; $j < count($result[$i]); $j++) {
                        if ($j>0) {
                            $template .= 
                            "<td>".$result[$i][$j]."</td>";
                        } elseif ($j == 0) {
                            $postId = $result[$i][$j]; // lấy id của post
                        }
                    }
                    $printContentFromDb .= // lưu bài vào biến
                        "<tr>
                            <th scope='row'>$post</th>
                            $template
                            <td><span><a href='edit.php?type=post&id=$postId' class='btn btn-info'>Edit</a></span><span><button data-id='$postId' class='btn btn-danger delete'>Remove</button></span></td>
                        </tr>";
                    $post++;
                }
            } else { // nếu ko
                pagination($pagination, $count, $total, $typeRequest);
                if ($pagination==$count) {
                    $post=(($pagination-1)*10+1); // bài đầu tiên là (($pagination-1)*10+1). vd pagination = 2 thì bài đầu tiên là ((2-1)*10+1) == 11
                    for ($i=(($pagination-1)*10); $i < $resultC['count']; $i++) {  // vòng lặp in bài
                        $template = ''; // reset biến template
                        for ($j=0; $j < count($result[$i]); $j++) {
                            if ($j>0) {
                                $template .= 
                                "<td>".$result[$i][$j]."</td>";
                            } elseif ($j == 0) {
                                $postId = $result[$i][$j]; // lấy id của post
                            }
                        }
                        $printContentFromDb .= // lưu bài vào biến
                            "<tr>
                                <th scope='row'>$post</th>
                                $template
                                <td><span><a href='edit.php?type=post&id=$postId' class='btn btn-info'>Edit</a></span><span><button data-id='$postId' class='btn btn-danger delete'>Remove</button></span></td>
                            </tr>";
                        $post++;
                    }
                } else {
                    $post=(($pagination-1)*10+1); // bài đầu tiên là (($pagination-1)*10+1). vd pagination = 2 thì bài đầu tiên là ((2-1)*10+1) == 11
                    for ($i=(($pagination-1)*10); $i < ($pagination*10); $i++) {  // vòng lặp in bài
                        $template = ''; // reset biến template
                        for ($j=0; $j < count($result[$i]); $j++) {
                            if ($j>0) {
                                $template .= 
                                "<td>".$result[$i][$j]."</td>";
                            } elseif ($j == 0) {
                                $postId = $result[$i][$j]; // lấy id của post
                            }
                        }
                        $printContentFromDb .= // lưu bài vào biến
                            "<tr>
                                <th scope='row'>$post</th>
                                $template
                                <td><span><a href='edit.php?type=post&id=$postId' class='btn btn-info'>Edit</a></span><span><button data-id='$postId' class='btn btn-danger delete'>Remove</button></span></td>
                            </tr>";
                        $post++;
                    }
                }
            }
            break;
        case 'pages':
            $getFDb = $db->selectCol('pages', 'id', 'title', 'author'); // lấy id, title, tác giả từ db
            $getCFDb = $db->selectCol('pages', 'COUNT(id) AS count'); // đếm số lượng
            $result = mysqli_fetch_all($getFDb);
            $resultC = mysqli_fetch_assoc($getCFDb);
            $total = $resultC['count'];
            $count = $resultC['count'];
            $count = ceil($count/10); // số trang pagination
            if ($resultC['count'] < 10) { // nếu số lượng bài dưới 10
                $page=1; // bài đầu tiên là 1
                for ($i=0; $i < $resultC['count']; $i++) {  // vòng lặp in bài
                    $template = ''; // reset biến template
                    for ($j=0; $j < count($result[$i]); $j++) {
                        if ($j>0) {
                            $template .= 
                            "<td>".$result[$i][$j]."</td>";
                        } elseif ($j == 0) {
                            $pageId = $result[$i][$j]; // lấy id của page
                        }
                    }
                    $printContentFromDb .= // lưu bài vào biến
                        "<tr>
                            <th scope='row'>$page</th>
                            $template
                            <td><span><a href='edit.php?type=page&id=$pageId' class='btn btn-info'>Edit</a></span><span><button data-id='$pageId' class='btn btn-danger delete'>Remove</button></span></td>
                        </tr>";
                    $page++;
                }
            } else { // nếu ko
                pagination($pagination, $count, $total, $typeRequest);
                if ($pagination==$count) {
                    $page=(($pagination-1)*10+1); // bài đầu tiên là (($pagination-1)*10+1). vd pagination = 2 thì bài đầu tiên là ((2-1)*10+1) == 11
                    for ($i=(($pagination-1)*10); $i < $resultC['count']; $i++) {  // vòng lặp in bài
                        $template = ''; // reset biến template
                        for ($j=0; $j < count($result[$i]); $j++) {
                            if ($j>0) {
                                $template .= 
                                "<td>".$result[$i][$j]."</td>";
                            } elseif ($j == 0) {
                                $pageId = $result[$i][$j]; // lấy id của page
                            }
                        }
                        $printContentFromDb .= // lưu bài vào biến
                            "<tr>
                                <th scope='row'>$page</th>
                                $template
                                <td><span><a href='edit.php?type=page&id=$pageId' class='btn btn-info'>Edit</a></span><span><button data-id='$pageId' class='btn btn-danger delete'>Remove</button></span></td>
                            </tr>";
                        $page++;
                    }
                } else {
                    $page=(($pagination-1)*10+1); // bài đầu tiên là (($pagination-1)*10+1). vd pagination = 2 thì bài đầu tiên là ((2-1)*10+1) == 11
                    for ($i=(($pagination-1)*10); $i < ($pagination*10); $i++) {  // vòng lặp in bài
                        $template = ''; // reset biến template
                        for ($j=0; $j < count($result[$i]); $j++) {
                            if ($j>0) {
                                $template .= 
                                "<td>".$result[$i][$j]."</td>";
                            } elseif ($j == 0) {
                                $pageId = $result[$i][$j]; // lấy id của page
                            }
                        }
                        $printContentFromDb .= // lưu bài vào biến
                            "<tr>
                                <th scope='row'>$page</th>
                                $template
                                <td><span><a href='edit.php?type=page&id=$pageId' class='btn btn-info'>Edit</a></span><span><button data-id='$pageId' class='btn btn-danger delete'>Remove</button></span></td>
                            </tr>";
                        $page++;
                    }
                }
            }
            break;
        case 'categories':
            $getFDb = $db->selectCol('categories', 'id', 'title', 'author'); // lấy id, title, tác giả từ db
            $getCFDb = $db->selectCol('categories', 'COUNT(id) AS count'); // đếm số lượng
            $result = mysqli_fetch_all($getFDb);
            $resultC = mysqli_fetch_assoc($getCFDb);
            if ($resultC['count'] < 10) { // nếu số lượng bài dưới 10
                $category=1; // bài đầu tiên là 1
                for ($i=0; $i < $resultC['count']; $i++) {  // vòng lặp in bài
                    $template = ''; // reset biến template
                    for ($j=0; $j < count($result[$i]); $j++) {
                        if ($j>0) {
                            $template .= 
                            "<td>".$result[$i][$j]."</td>";
                        } elseif ($j == 0) {
                            $categoryId = $result[$i][$j]; // lấy id của category
                        }
                    }
                    $printContentFromDb .= // lưu bài vào biến
                        "<tr>
                            <th scope='row'>$category</th>
                            $template
                            <td><span><a href='edit.php?type=category&id=$categoryId' class='btn btn-info'>Edit</a></span><span><button data-id='$categoryId' class='btn btn-danger delete'>Remove</button></span></td>
                        </tr>";
                    $category++;
                }
            } elseif ($pagination==1) {
                
            } else {

            }
            break;
    }

    switch ($typeRequest) {
        case 'posts':
            $windowLocation = 'window.location.assign(`edit.php?type=post&id=${id}&delete=true`)';
            break;
        case 'pages':
            $windowLocation = 'window.location.assign(`edit.php?type=page&id=${id}&delete=true`)';
            break;
        case 'categories':
            $windowLocation = 'window.location.assign(`edit.php?type=category&id=${id}&delete=true`)';
            break;
    }
    if ($pagination==''){
        $pagination = 1;
    }
    $css =
"<style>
.view.$typeRequest > a {
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
    $js =
"<script>
    let collapseCol = false;
    $('#paginate-$pagination').addClass('active');
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
    $('.delete').on('click', function (){
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Deleted!',
                    'The $typeRequest has been deleted.',
                    'success'
                )
                id = $(this).attr('data-id');
                $windowLocation
            }
    })
});

</script>";
    
    $viewPosts = 
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h1 class='text-center'>Viewing posts</h1>
                <span><a href='create.php?type=post' class='btn btn-info'>Add new post</a><span/>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Title</th>
                            <th scope='col'>Author</th>
                            <th scope='col'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        $printContentFromDb
                    </tbody>
                </table>
                $htmlPagination
            </div>
        </row>
    </div>
</main>";
    $viewPages = 
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h1 class='text-center'>Viewing pages</h1>
                <span><a href='create.php?type=page' class='btn btn-info'>Add new page</a><span/>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Title</th>
                            <th scope='col'>Author</th>
                            <th scope='col'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        $printContentFromDb
                    </tbody>
                </table>
                $htmlPagination
            </div>
        </row>
    </div>
</main>";
    $viewCategories =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h1 class='text-center'>Viewing categories</h1>
                <span><a href='create.php?type=category' class='btn btn-info'>Add new category</a><span/>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Title</th>
                            <th scope='col'>Author</th>
                            <th scope='col'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        $printContentFromDb
                    </tbody>
                </table>
            </div>
        </row>
    </div>
</main>";
    if ($errorCheck) {
        $viewPosts = $viewPages = $viewCategories = 
        "<main>
            <div class='container'>
                <div class='row'>
                    <div class='col'>
                        <h1 class='text-center'>Viewing $typeRequest</h1>
                        <div class='alert alert-warning' role='alert'>
                            $error
                        </div>
                    </div>
                </row>
            </div>
        </main>";
    }
    require_once('themes/default/header.php');
    require_once('themes/default/modules/mainMenus.php');
    switch ($typeRequest) {
        case 'posts':
            echo $viewPosts;
            break;
        case 'pages':
            echo $viewPages;
            break;
        case 'categories':
            echo $viewCategories;
            break;
        default:
            echo '<main><h1>Invalid request, please try again!</h1></main>';
            break;
    }
    require_once('themes/default/footer.php');
    echo $css.$js;
?>