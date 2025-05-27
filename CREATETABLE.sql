

CREATE DATABASE ConneCre;

use ConneCre;

CREATE TABLE userinfo(
    user_num INTEGER AUTO_INCREMENT PRIMARY KEY,    -- 主キー
    user_id VARCHAR(30) UNIQUE NOT NULL,            -- ユーザーID
    user_pw VARCHAR(20) NOT NULL,                   -- パスワード
    user_name VARCHAR(20) NOT NULL,                 -- ユーザー名
    user_role TINYINT,                              -- ユーザーの役割(0:バックエンド 1:フロントエンド 2:フルスタック)
    user_profile VARCHAR(1000),                     -- 自己紹介文
    user_icon VARCHAR(50) DEFAULT 'img/default.png' -- アイコンのファイルパス
);

CREATE TABLE recruit(
    recruit_num INTEGER AUTO_INCREMENT PRIMARY KEY, -- 主キー
    recruit_title VARCHAR(50) NOT NULL,             -- 募集タイトル
    recruit_datetime DATETIME NOT NULL,             -- 募集開始日時
    recruiter_id VARCHAR(30) NOT NULL,              -- 募集者ID
    recruit_role TINYINT,                           -- 募集対象の役割
    recruit_location TINYINT NOT NULL,              -- ハッカソンの開催地   
    recruit_capacity TINYINT NOT NULL,              -- 募集人数
    recruit_content VARCHAR(1000),                  -- 募集本文
    recruit_status TINYINT NOT NULL,                -- 募集状態(0:募集終了 1:募集中)
    edited TINYINT NOT NULL,                        -- 編集(0:未編集 1:編集済み)

    -- recruiter_idの外部キー制約
    CONSTRAINT fk_recruiter_id FOREIGN KEY (recruiter_id)
        REFERENCES userinfo(user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE apply(
    apply_num INTEGER AUTO_INCREMENT PRIMARY KEY,   -- 主キー
    recruit_num INTEGER,                            -- 募集番号
    apply_datetime DATETIME NOT NULL,               -- 応募日時
    applicant_id VARCHAR(20),                       -- 応募者ID
    apply_content VARCHAR(1000),                    -- 応募本文
    apply_status TINYINT NOT NULL DEFAULT 1,        -- 応募状態(0:拒否 1:未承認 2:承認)

    CONSTRAINT fk_recruit_num FOREIGN KEY (recruit_num)
        REFERENCES recruit(recruit_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,
    
    CONSTRAINT fk_applicant_id FOREIGN KEY (applicant_id)
        REFERENCES userinfo(user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE history(
    history_num INTEGER AUTO_INCREMENT PRIMARY KEY, -- 主キー
    user_id VARCHAR(30),                            -- ユーザーID
    history_title VARCHAR(50),                      -- 過去作タイトル
    history_discription VARCHAR(1000),              -- 過去作説明

    CONSTRAINT fk_user_id FOREIGN KEY (user_id)
        REFERENCES userinfo(user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE recruit_chat_room(
    room_num INTEGER AUTO_INCREMENT PRIMARY KEY,    -- 主キー
    recruit_num INTEGER,                            -- 募集番号
    active TINYINT NOT NULL,                        -- アクティブ状態か(0:非アクティブ 1:アクティブ)

    CONSTRAINT fk_cr_recruit_num FOREIGN KEY (recruit_num)
        REFERENCES recruit (recruit_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE recruit_chat(
    chat_num INTEGER AUTO_INCREMENT PRIMARY KEY,    -- 主キー
    sender_id VARCHAR(30),                          -- 送信者ID
    chat_datetime DATETIME NOT NULL,                -- 送信日時
    chat_content VARCHAR(1000),                     -- 送信内容
    room_num INTEGER,                               -- 部屋番号

    CONSTRAINT fk_sender_id FOREIGN KEY (sender_id)
        REFERENCES userinfo (user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,
    
    CONSTRAINT fk_room_num FOREIGN KEY (room_num)
        REFERENCES recruit (recruit_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE jam(
    jam_num INTEGER AUTO_INCREMENT PRIMARY KEY,     -- 主キー
    hackason_name VARCHAR(40) NOT NULL,             -- ハッカソン名
    hackason_location TINYINT NOT NULL,             -- ハッカソン開催地(都道府県コード)
    host_id VARCHAR(30),                            -- 開催者ID
    jam_start_time DATETIME NOT NULL,               -- ジャム開始時間
    jam_period_time DATETIME NOT NULL,              -- ジャム終了時間
    team_min TINYINT NOT NULL,                      -- チーム最大人数
    team_max TINYINT NOT NULL,                      -- チーム最少人数
    active TINYINT NOT NULL DEFAULT 1,              -- ジャムのアクティブ状態(1:active 0:終了)
    jam_password VARCHAR(8) NOT NULL,               -- ジャムのパスワード

    CONSTRAINT fk_host_id FOREIGN KEY (host_id)
        REFERENCES userinfo (user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE jam_chat_room(     
    room_num INTEGER AUTO_INCREMENT PRIMARY KEY,    -- 主キー
    jam_num INTEGER,                                -- ジャム番号
    active TINYINT NOT NULL,                        -- アクティブ状態

    CONSTRAINT fk_jam_num FOREIGN KEY (jam_num)
        REFERENCES jam (jam_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE jam_member(
    num INTEGER AUTO_INCREMENT PRIMARY KEY,         -- 主キー
    member_id VARCHAR(20) NOT NULL,          -- メンバーのユーザID
    room_num INTEGER NOT NULL,                      -- ジャムの部屋番号

    CONSTRAINT fk_member_id FOREIGN KEY (member_id)
        REFERENCES userinfo (user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,

    CONSTRAINT fk_member_room_num FOREIGN KEY (room_num)
        REFERENCES jam_chat_room (room_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE jam_chat(
    chat_num INTEGER AUTO_INCREMENT PRIMARY KEY,    -- 主キー
    sender_id VARCHAR(20),                          -- 送信者ID
    chat_datetime DATETIME NOT NULL,                -- 送信日時
    chat_content VARCHAR(1000),                     -- 送信内容
    room_num INTEGER,                               -- 部屋番号

    CONSTRAINT fk_jam_sender_id FOREIGN KEY (sender_id)
        REFERENCES userinfo (user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,

    CONSTRAINT fk_jam_room_num FOREIGN KEY (room_num)
        REFERENCES jam_chat_room (room_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE jam_apply(
    apply_num INTEGER AUTO_INCREMENT PRIMARY KEY,   -- 主キー
    jam_num INTEGER,                                -- ジャム番号
    applicant_id VARCHAR(20),                -- 参加者ID
    applicant_role TINYINT NOT NULL,                -- 参加者の役割

    CONSTRAINT fk_apply_jam_num FOREIGN KEY (jam_num)
        REFERENCES jam (jam_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,

    CONSTRAINT fk_jam_applicant_id FOREIGN KEY (applicant_id)
        REFERENCES userinfo (user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE user_tech(
    user_id VARCHAR(20),                            -- ユーザーID
    user_tech VARCHAR(20) NOT NULL,                 -- ユーザーの技術スタック

    CONSTRAINT fk_tech_user_id FOREIGN KEY (user_id)
        REFERENCES userinfo (user_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE jam_tech(                          
    applicant_id VARCHAR(20),                                -- 応募者ID
    jam_tech VARCHAR(20) NOT NULL,                  -- ジャム応募の技術スタック

    CONSTRAINT fk_jam_tech FOREIGN KEY (applicant_id)
        REFERENCES jam_apply (applicant_id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);

CREATE TABLE recruit_tech(
    recruit_num INTEGER,                            -- 募集番号
    recruit_tech VARCHAR(20) NOT NULL,              -- 募集の技術スタック

    CONSTRAINT fk_tech_recruit_num FOREIGN KEY (recruit_num)
        REFERENCES recruit (recruit_num)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
);