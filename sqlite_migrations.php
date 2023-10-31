<?php

use DummyNewsSite\Repository\SQLiteConnection;

require_once 'vendor/autoload.php';

if (false === file_exists(__DIR__.'/src/Repository/database.db')) {
    $connection = (new SQLiteConnection())->connect();

    $connection->query(
        <<<SQL
create table user
(
    id       integer not null
        constraint user_pk
            primary key autoincrement,
    username TEXT    not null,
    password TEXT    not null
);
SQL
    );

    $password = password_hash('test', PASSWORD_BCRYPT);

    $connection->query(
        <<<SQL
insert into user (username, password)
values ("admin", "$password")
SQL
    );

    $connection->query(
        <<<SQL
create table user_session
(
    session_id TEXT    not null
        constraint user_session_pk
            primary key,
    user_id    integer not null
        constraint user_session_user_id_fk
            references user
);
SQL
    );

    $connection->query(
        <<<SQL
create table news
(
    id      integer not null
        constraint news_pk
            primary key autoincrement,
    title   TEXT,
    content TEXT,
    user_id integer not null
        constraint news_user_id_fk
            references user
);
SQL
    );
}