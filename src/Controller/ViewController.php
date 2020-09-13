<?php
namespace Controller;

use App\DB;

class ViewController {
    function main(){
        view("main", [
            "notices" => DB::fetchAll("SELECT * FROM notices ORDER BY id DESC"),
            "artworks" => DB::fetchAll("SELECT A.*, user_name FROM artworks A, users U WHERE A.uid = U.id AND rm_reason IS NULL ORDER BY A.id DESC")
        ]);
    }

    function intro(){
        view("intro");
    }

    function roadmap(){
        view("roadmap");
    }

    function signUp(){
        view("sign-up");
    }

    function signIn(){
        view("sign-in");
    }

    function notices(){
        $notices = pagination(DB::fetchAll("SELECT * FROM notices ORDER BY id DESC"));
        
        view("notices", compact("notices"));
    }

    function notice($id){
        $notice = DB::find("notices", $id);
        if(!$notice) back("대상을 찾을 수 없습니다.");
        $notice->files = array_map(function($file){
            return fileinfo($file);
        }, json_decode($notice->files));

        view("notice", compact("notice"));
    }

    function inquires(){
        if(admin()){
            view("inquires-admin", [
                "inquires" => DB::fetchAll("SELECT I.*, answer, answered_at, user_name, user_email
                                            FROM inquires I
                                            LEFT JOIN users U ON U.id = I.uid
                                            LEFT JOIN answers A ON A.iid = I.id
                                            ORDER BY id DESC")
            ]);
        } 
        else if(user()) {
            view("inquires-user", [
                "inquires" => DB::fetchAll("SELECT I.*, answer, answered_at, user_name, user_email
                                            FROM inquires I
                                            LEFT JOIN users U ON U.id = I.uid
                                            LEFT JOIN answers A ON A.iid = I.id
                                            WHERE U.id = ?
                                            ORDER BY id DESC", [user()->id])
            ]);
        } else go("/sign-in", "로그인 후 이용하실 수 있습니다.");
    }

    function store(){
        view("store");
    }

    function companies(){
        $companies = DB::fetchAll("SELECT U.*, IFNULL(totalPoint, 0) totalPoint
                                    FROM users U
                                    LEFT JOIN (SELECT SUM(point) totalPoint, uid FROM history GROUP BY uid) H ON H.uid = U.id
                                    WHERE U.type = 'company'");

        $rankers = array_slice($companies, 0, 4);
        $companies = pagination(array_slice($companies, 4));

        view("companies", compact("companies", "rankers"));
    }

    function entry(){
        view("entry");
    }

    function artworks(){
        global $tags, $searches;
        $tags = [];
        $searches = isset($_GET['searches']) && json_decode($_GET['searches']) ? json_decode($_GET['searches']) : [];
        
        $artworks = array_map(function($artwork){
            global $tags;
            $artwork->hash_tags = json_decode($artwork->hash_tags);
            array_push($tags, ...$artwork->hash_tags);
            return $artwork;
        }, DB::fetchAll("SELECT A.*, user_name, type, IFNULL(score, 0) score
                        FROM artworks A
                        LEFT JOIN users U ON U.id = A.uid
                        LEFT JOIN (SELECT ROUND(AVG(score), 1) score, aid  FROM scores GROUP BY aid) S ON S.aid = A.id
                        WHERE rm_reason IS NULL
                        ORDER BY id DESC"));

        if(count($searches) !== 0){
            $artworks = array_filter($artworks, function($artwork){
                global $searches;
    
                foreach($artwork->hash_tags as $tag){
                    if(array_search($tag, $searches) === false)
                        return false;
                }
                return true;
            });
        }
        $artworks = pagination($artworks);


        $rankers = array_map(function($artwork){
            $artwork->hash_tags = json_decode($artwork->hash_tags);
            return $artwork;
        }, DB::fetchAll("SELECT A.*, user_name, type, IFNULL(score, 0) score
                        FROM artworks A
                        LEFT JOIN users U ON U.id = A.uid
                        LEFT JOIN (SELECT ROUND(AVG(score), 1) score, aid  FROM scores GROUP BY aid) S ON S.aid = A.id
                        WHERE rm_reason IS NULL AND A.created_at >= ?
                        ORDER BY score DESC
                        LIMIT 0, 4", [date("Y-m-d", strtotime("-7 Day"))]));

        $myList = !user() ? [] :  array_map(function($artwork){
            $artwork->hash_tags = json_decode($artwork->hash_tags);
            return $artwork;
        }, DB::fetchAll("SELECT A.*, user_name, type, IFNULL(score, 0) score
                        FROM artworks A
                        LEFT JOIN users U ON U.id = A.uid
                        LEFT JOIN (SELECT ROUND(AVG(score), 1) score, aid FROM scores GROUP BY aid) S ON S.aid = A.id
                        WHERE A.uid = ?", [user()->id]));

        view("artworks", compact("tags", "searches", "artworks", "myList", "rankers"));
    }

    function artwork($id){
        $artwork = DB::fetch("SELECT A.*, IFNULL(score, 0) score, M.aid reviewed
                                FROM artworks A
                                LEFT JOIN (SELECT ROUND(AVG(score), 1) score, aid FROM scores GROUP BY aid) S ON S.aid = A.id
                                LEFT JOIN (SELECT aid FROM scores WHERE uid = ?) M ON M.aid = A.id
                                WHERE id = ?", [ user() ? user()->id: '', $id]);

        if(!$artwork) back("대상을 찾을 수 없습니다.");
        $artwork->hash_tags = json_decode($artwork->hash_tags);
        $writer = DB::find("users", $artwork->uid);

        view("artwork", compact("artwork", "writer"));
    }
}