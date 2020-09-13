<?php
use App\Router;

Router::get("/init", "ActionController@init");

Router::get("/", "ViewController@main");
Router::get("/intro", "ViewController@intro");
Router::get("/roadmap", "ViewController@roadmap");

/**
 * 사용자 인증
 */
Router::get("/sign-in", "ViewController@signIn", "guest");
Router::get("/sign-up", "ViewController@signUp", "guest");

Router::post("/sign-in", "ActionController@signIn", "guest");
Router::post("/sign-up", "ActionController@signUp", "guest");
Router::get("/sign-out", "ActionController@signOut", "login");

Router::get("/api/users/{user_email}", "ApiController@getUser");

/**
 * 알려드립니다
 */
Router::get("/notices", "ViewController@notices", "login");
Router::get("/notices/{id}", "ViewController@notice", "login");

Router::post("/insert/notices", "ActionController@insertNotice", "admin");
Router::post("/update/notices/{id}", "ActionController@updateNotice", "admin");
Router::get("/delete/notices/{id}", "ActionController@deleteNotice", "admin");

Router::get("/download/{filename}", "ActionController@download");

/**
 * 1:1문의
 */
Router::get("/inquires", "ViewController@inquires", "login");

Router::post("/insert/inquires", "ActionController@insertInquire", "login");
Router::post("/insert/answers", "ActionController@insertAnswer", "admin");

/**
 * 온라인스토어
 */
Router::get("/store", "ViewController@store", "login");

Router::post("/insert/papers", "ActionController@insertPaper", "company");
Router::post("/insert/inventory", "ActionController@insertInventory", "user");

Router::get("/api/papers", "ApiController@getPapers");

/**
 * 한지 업체
 */
Router::get("/companies", "ViewController@companies");

/**
 * 출품신청
 */
Router::get("/entry", "ViewController@entry", "login");

Router::post("/insert/artworks", "ActionController@insertArtwork", "user");
Router::post("/update/inventory/{id}", "ActionController@updateInventory");
Router::post("/delete/inventory/{id}", "ActionController@deleteInventory");

Router::get("/api/inventory", "ApiController@getInventory");

/**
 * 참가작품
 */
Router::get("/artworks", "ViewController@artworks");
Router::get("/artworks/{id}", "ViewController@artwork");

Router::post("/update/artworks/{id}", "ActionController@updateArtwork");
Router::get("/delete/artworks/{id}", "ActionController@deleteArtwork");
Router::post("/delete-admin/artworks/{id}", "ActionController@deleteArtworkByAdmin");
Router::post("/insert/scores", "ActionController@insertScore");

Router::start();