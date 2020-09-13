<?php
namespace Controller;

use App\DB;

class ActionController {
    function init(){
        $exist = DB::who("admin");
        if(!$exist) {
            DB::query("INSERT INTO users (user_email, password, user_name, type) VALUES (?, ?, ? ,?)", [
                "admin", "1234", "관리자", "admin"
            ]);
        }
    }

    function signIn(){
        checkEmpty();
        extract($_POST);

        $user = DB::who($user_email);
        if(!$user) back("아이디와 일치하는 회원이 존재하지 않습니다.");
        if($user->password !== $password) back("비밀번호가 일치하지 않습니다.");

        $_SESSION['user'] = $user;

        go("/", "로그인 되었습니다.");
    }

    function signUp(){
        checkEmpty();
        extract($_POST);

        $image = $_FILES['image'];
        $filename = time() . "-" . $image['name'];
        move_uploaded_file($image['tmp_name'], UPLOAD."/$filename");
        
        DB::query("INSERT INTO users(user_email, password, user_name, image, type) VALUES (?, ?, ?, ?, ?)", [
            $user_email, $password, $user_name, $filename, $type
        ]);

        go("/", "회원가입 되었습니다.");
    }

    function signOut(){
        unset($_SESSION['user']);
        go("/", "로그아웃 되었습니다.");
    }

    function insertNotice(){
        checkEmpty();
        extract($_POST);

        $files = $_FILES['files'];
        $fileLength = count($files['name']);
        $ff = $files['name'][0];
        $filenames = [];

        if($ff){
            for($i = 0; $i < $fileLength; $i++){
                $fname = $files['name'][$i];
                $tmp_name = $files['tmp_name'][$i];
                $size = $files['size'][$i];
                $filename = time() . "-" . $fname;
                
                if($size === 0 || $size > 1024 * 1024 * 10) back("파일은 10MB 이하만 업로드 가능합니다.");
                if($i > 3) back("파일은 4개까지만 업로드 가능합니다.");
                
                $filenames[] = $filename;
                move_uploaded_file($tmp_name, UPLOAD."/$filename");
            }
        }

        DB::query("INSERT INTO notices(title, content, files) VALUES (?, ?, ?)", [$title, $content, json_encode($filenames)]);

        go("/notices", "추가했습니다.");
    }

    function updateNotice($id){
        $notice = DB::find("notices", $id);
        if(!$notice) back("대상을 찾을 수 없습니다.");

        checkEmpty();
        extract($_POST);

        $files = $_FILES['files'];
        $fileLength = count($files['name']);
        $ff = $files['name'][0];
        $filenames = json_decode($notice->files);

        if($ff){
            $filenames = [];
            for($i = 0; $i < $fileLength; $i++){
                $fname = $files['name'][$i];
                $tmp_name = $files['tmp_name'][$i];
                $size = $files['size'][$i];
                $filename = time() . "-" . $fname;
                
                if($size == 0 || $size > 1024 * 1024 * 10) back("파일은 10MB 이하만 업로드 가능합니다.");
                if($i > 3) back("파일은 4개까지만 업로드 가능합니다.");
                
                $filenames[] = $filename;
                move_uploaded_file($tmp_name, UPLOAD."/$filename");
            }
        }

        DB::query("UPDATE notices SET title = ?, content = ?, files = ? WHERE id = ?", [$title, $content, json_encode($filenames), $id]);

        go("/notices/$id", "수정했습니다.");
    }

    function deleteNotice($id){
        $notice = DB::find("notices", $id);
        if(!$notice) back("대상을 찾을 수 없습니다.");

        DB::query("DELETE FROM notices WHERE id = ?", [$id]);
        go("/notices", "삭제했습니다.");
    }

    function download($filename){
        $filename = urldecode($filename);
        $path = UPLOAD."/$filename";

        if(is_file($path)){
            header("Content-Disposition: attachement; filename=$filename");
            readfile($path);
        }
    }

    function insertInquire(){
        checkEmpty();
        extract($_POST);

        DB::query("INSERT INTO inquires(uid, title, content) VALUES (?, ?, ?)", [user()->id, $title, $content]);

        go("/inquires", "추가했습니다.");
    }

    function insertAnswer(){
        checkEmpty();
        extract($_POST);
        $inquire = DB::find("inquires", $iid);
        if(!$inquire) back("대상을 찾을 수 없습니다.");

        DB::query("INSERT INTO answers(iid, answer) VALUES (?, ?)", [$iid, $answer]);

        go("/inquires", "추가했습니다.");
    }

    function insertPaper(){
        checkEmpty();
        extract($_POST);

        $image = $_FILES['image'];
        $filename = time(). "-" . $image['name'];
        move_uploaded_file($image['tmp_name'], UPLOAD."/$filename");

        DB::query("INSERT INTO papers(uid, paper_name, width_size, height_size, point, hash_tags, image) VALUES (?, ?, ?, ?, ?, ?, ?)", [
            user()->id, $paper_name, $width_size, $height_size, $point, $hash_tags, "/uploads/" . $filename
        ]);

        $pid = DB::lastInsertId();
        DB::query("INSERT INTO inventory(uid, pid, hasCount) VALUES (?, ?, -1)", [user()->id, $pid]);

        go("/store", "추가되었습니다.");
    }

    function insertInventory(){
        checkEmpty();
        extract($_POST);

        if(user()->point < $totalPoint){
            back("포인트가 부족하여 구매하실 수 없습니다.");
        }

        foreach(json_decode($cartList) as $item){
            $point = $item->point * $item->buyCount;

            $exist = DB::fetch("SELECT * FROM inventory WHERE uid = ? AND pid = ?", [user()->id, $item->id]);
            if($exist) {
                DB::query("UPDATE inventory SET hasCount = hasCount + ? WHERE id = ?", [$point, $exist]);
            } else {
                DB::query("INSERT INTO inventory(uid, pid, hasCount) VALUES (?, ?, ?)", [user()->id, $item->id, $item->buyCount]);
            }

            DB::query("UPDATE users SET point = point - ? WHERE id = ?", [$point, user()->id]);
            DB::query("UPDATE users SET point = point + ? WHERE id = ?", [$point, $item->uid]);
            DB::query("INSERT INTO history(uid, point) VALUES (?, ?)", [$item->uid, $point]);
        }

        go("/store", "총 {$totalCount}개의 한지가 구매되었습니다.");
    }

    function insertArtwork(){
        checkEmpty();
        extract($_POST);

        $filename = base64_upload($image);
        DB::query("INSERT INTO artworks(uid, title, content, hash_tags, image) VALUES (?, ?, ?, ?, ?)", [user()->id, $title, $content, $hash_tags, $filename]);

        go("/artworks", "추가되었습니다.");
    }

    function updateInventory($id){  
        $item = DB::find("inventory", $id);        
        if(!$item || $item->uid !== user()->id) return;
        checkEmpty();
        extract($_POST);

        DB::query("UPDATE inventory SET hasCount = ? WHERE id = ?", [$count, $id]);
    }

    function deleteInventory($id){
        $item = DB::find("inventory", $id);        
        if(!$item || $item->uid !== user()->id) return;

        DB::query("DELETE FROM inventory WHERE id = ?", [$id]);
    }

    function updateArtwork($id){
        $artwork = DB::find("artworks", $id);
        if(!$artwork) back("대상을 찾을 수 없습니다.");

        checkEmpty();
        extract($_POST);

        DB::query("UPDATE artworks SET title = ?, content = ?, hash_tags = ? WHERE id = ?", [$title, $content, $hash_tags, $id]);
        go("/artworks/$id", "수정되었습니다.");
    }

    function deleteArtwork($id){
        $artwork = DB::find("artworks", $id);
        if(!$artwork) back("대상을 찾을 수 없습니다.");

        DB::query("DELETE FROM artworks WHERE id = ?", [$id]);
        go("/artworks", "삭제되었습니다.");
    }

    function deleteArtworkByAdmin($id){
        $artwork = DB::find("artworks", $id);
        if(!$artwork) back("대상을 찾을 수 없습니다.");
        checkEmpty();
        extract($_POST);

        DB::query("UPDATE artworks SET rm_reason = ? WHERE id =?", [$rm_reason, $id]);

        go("/artworks", "삭제되었습니다.");
    }

    function insertScore(){
        checkEmpty();
        extract($_POST);

        $artwork = DB::find("artworks", $aid);
        if(!$artwork) back("대상을 찾을 수 없습니다.");       
 
        DB::query("INSERT INTO scores (uid, aid, score) VALUES (?, ?, ?)" ,[user()->id, $aid, $score]);
        DB::query("UPDATE users SET point = point + ? WHERE id = ?", [$score * 100, $artwork->uid]);

        go("/artworks/$aid", "평점을 매겼습니다.");
    }
}