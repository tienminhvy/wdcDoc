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
                    $print .= // lưu bài vào biến
                        "<tr>
                            <th scope='row'>$post</th>
                            $template
                            <td><span><a href='edit.php?type=post&id=$postId' class='btn btn-info'>Edit</a></span><span><button data-id='$postId' class='btn btn-danger delete'>Remove</button></span></td>
                        </tr>";
                    $post++;
                }
            } elseif ($pagination==1) {
                
            } else {

            }
            break;
        case 'pages':
            $getFDb = $db->selectCol('pages', 'id', 'title', 'author'); // lấy id, title, tác giả từ db
            $getCFDb = $db->selectCol('pages', 'COUNT(id) AS count'); // đếm số lượng
            $result = mysqli_fetch_all($getFDb);
            $resultC = mysqli_fetch_assoc($getCFDb);
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
                    $print .= // lưu bài vào biến
                        "<tr>
                            <th scope='row'>$page</th>
                            $template
                            <td><span><a href='edit.php?type=page&id=$pageId' class='btn btn-info'>Edit</a></span><span><button data-id='$pageId' class='btn btn-danger delete'>Remove</button></span></td>
                        </tr>";
                    $page++;
                }
            } elseif ($pagination==1) {
                
            } else {

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
                    $print .= // lưu bài vào biến
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

    $js =
"<script>
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
                        $print
                    </tbody>
                </table>
            </div>
        </row>
    </div>
</main>";
    $viewPages = 
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
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
                        $print
                    </tbody>
                </table>
            </div>
        </row>
    </div>
</main>";
    $viewCategories =
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
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
                        $print
                    </tbody>
                </table>
            </div>
        </row>
    </div>
</main>";
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
    echo $js;
?>