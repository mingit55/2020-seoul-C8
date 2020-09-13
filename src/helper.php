<?php
use App\DB;

function dump(){
    foreach(func_get_args() as $arg){
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
}

function dd(){
    foreach(func_get_args() as $arg){
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
    exit;
}

function user(){
    if(isset($_SESSION['user'])){
        $user = DB::who($_SESSION['user']->user_email);

        if(!$user) {
            unset($_SESSION['user']);
            go("/", "회원정보를 찾을 수 없습니다. 로그아웃 됩니다.");
        }
        
        return $user;
    } else return false;
}

function company(){
    $user = user();
    return $user && $user->type == 'company' ? $user : false;
}

function admin(){
    $user = user();
    return $user && $user->type == 'admin' ? $user : false;
}

function view($viewName, $data = []){
    extract($data);

    require VIEW."/header.php";
    require VIEW."/$viewName.php";
    require VIEW."/footer.php";
    exit;
}

function go($url, $message){
    echo "<script>";
    echo "alert('$message');";
    echo "location.href='$url';";
    echo "</script>";
    exit;
}

function back($message){
    echo "<script>";
    echo "alert('$message');";
    echo "history.back();";
    echo "</script>";
    exit;
}

function json_response($data){
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function checkEmpty(){
    foreach($_POST as $input){
        if(!is_array($input) && trim($input) == ""){
            back("모든 정보를 입력해 주세요!");
        }
    }
}

function extname($filename){
    return strtolower(substr($filename, strrpos($filename, ".")));
}

function isImage($filename){
    return array_search($filename, [".jpg", ".png", ".gif"]) !== false;
}

function fileinfo($filename){
    $name = substr($filename, strpos($filename, "-") + 1);
    $localpath = UPLOAD."/$filename";
    $size = is_file($localpath) ? number_format(filesize($localpath) / 1024, 2) . "KB" : "";  
    $viewname = "/uploads/$filename";

    return (object)compact("name", "localpath", "size", "viewname", "filename");
}

function pagination($data){
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] >= 1 ? $_GET['page'] : 1;

    define("COUNT", 9);
    define("BCOUNT", 5);

    $totalPage = ceil(count($data) / COUNT);
    $c_block = ceil($page / BCOUNT);

    $start = ($c_block - 1) * BCOUNT + 1;
    $end = $start + BCOUNT - 1;
    $end = $end > $totalPage ? $totalPage : $end;

    $prevPage = $start - 1;
    $prev = $prevPage >= 0;
    
    $nextPage = $end + 1;
    $next = $nextPage <= $totalPage;

    $data = array_slice($data, ($page-1) * COUNT, COUNT);

    return (object)compact("start", "end", "next", "nextPage", "prev", "prevPage", "data", "page");
}


function enc($output){
    return nl2br(str_replace(" ", "&nbsp;", htmlentities($output)));
}

function dt($date){
    return date("Y-m-d", strtotime($date));
}

function base64_upload($data){
    $temp = explode(";base64,", $data);
    $data = base64_decode($temp[1]);
    $filename = time() . ".jpg";
    file_put_contents(UPLOAD."/$filename", $data);
    return $filename;
}