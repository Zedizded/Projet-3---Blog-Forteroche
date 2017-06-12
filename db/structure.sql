drop table if exists t_comment;
drop table if exists t_user;
drop table if exists t_article;

create table t_article (
    art_id integer not null primary key auto_increment,
    art_title varchar(100) not null,
    art_content text not null,
    art_date datetime not null
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table t_user (
    usr_id integer not null primary key auto_increment,
    usr_name varchar(50) not null,
    usr_password varchar(88) not null,
    usr_salt varchar(23) not null,
    usr_role varchar(50) not null 
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table t_comment (
    com_id integer not null primary key auto_increment,
    com_author varchar(100) not null,
    com_email varchar(100) not null,
    com_content text(1000) not null,
    com_date datetime not null,
    com_flagged tinyint(1) not null,
    art_id integer not null,
    constraint fk_com_art foreign key(art_id) references t_article(art_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;