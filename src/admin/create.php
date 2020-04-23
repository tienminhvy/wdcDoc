<?php 
    define('isSet', 1);
?>
<?php require_once(__DIR__.'/themes/default/header.php'); ?>

<?php require_once(__DIR__.'/themes/default/modules/mainMenus.php') ?>

<?php 
    $viewOrCreate = $_GET['request'];
    $typeRequest = $_GET['type'];
    $css = 
"<style>
.$viewOrCreate.$type > a {
    background: black;
    color: rgb(231, 231, 231);
}
</style>";
    $invalidRequest =
"<main>Invalid request, please try again!<main>";
    $htmlCreatePost = 
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2>Add new post</h2>
                <input type='text' class='form-control'>
                <span>URL: </span>
            </div>
        </div>
        <div class='row'>
            <div class='col-8'>
                <textarea id='textarea'></textarea>
            </div>
            <div class='col-4'>
                <div class='card'>
                    <div class='card-body'>
                        <h5 class='card-title'>Configuration</h5>
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
</main>";
$htmlCreatePage = 
"<main>
    <div class='container'>
        <div class='row'>
            <div class='col'>
                <h2>Add new page</h2>
                <input type='text' class='form-control'>
                <span>URL: </span>
            </div>
        </div>
        <div class='row'>
            <div class='col-8'>
                <textarea id='textarea'></textarea>
            </div>
            <div class='col-4'>
                <div class='card'>
                    <div class='card-body'>
                        <h5 class='card-title'>Configuration</h5>
                        <span><button id='create' class='btn btn-info'>Create</button></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>";
    $htmlCreateCategory = 
"<main>
<div class='container'>
    <div class='row'>
        <div class='col'>
            <h2>Add new category</h2>
            <input type='text' class='form-control'>
            <span>URL: </span>
        </div>
    </div>
    <div class='row'>
        <div class='col-8'>
            <textarea id='textarea'></textarea>
        </div>
        <div class='col-4'>
            <div class='card'>
                <div class='card-body'>
                    <h5 class='card-title'>Configuration</h5>
                    <span><button id='create' class='btn btn-info'>Create</button></span>
                </div>
            </div>
        </div>
    </div>
</div>
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

<?php require_once(__DIR__.'/themes/default/footer.php'); ?>