create database hdcx;
use hdcx;
create table users(
	id int primary key auto_increment not null,
    name varchar(20) not null,
    xingming varchar(10) not null,
    password char(40) not null,
    contact varchar(13),
    email varchar(100)
)default charset=utf8;
create table admins(
	id int not null primary key auto_increment,
    name varchar(20) not null,
    password char(40) not null,
    insititute varchar(20),
    contact varchar(13),
    degree int default '1' comment "1是最高级管理员"
)default charset=utf8;
create table t_users(
	id int(11) primary key auto_increment not null,
    name varchar(20) not null,
    password char(40) not null,
    contact varchar(13),
    token varchar(40),
    token_exptime int,
    status int default '0' comment "0是邮箱未激活，1是已激活等待管理员审核，-1是管理员审核未通过",
    email varchar(100)
)default charset=utf8;
create table games(
	id int primary key auto_increment not null,
    name varchar(50) not null,
    intro text,
    date datetime,
    deadline datetime,
    sponsor varchar(20),
    admin_id int not null default '1'
)default charset=utf8;
create table members(
	id int primary key auto_increment not null,
    team_id int not null,
    name varchar(20) not null,
    gender char(2),
    age int,
    contact varchar(13),
    insititute varchar(20),
    class varchar(20),
    stunum int,
    idcard int,
    email varchar(50)
)default charset=utf8;
create table teams(
	id int primary key auto_increment not null,
	user_id int not null ,
    game_id int not null,
    team_name varchar(50),
    pro_name varchar(50),
    pro_intro text,
    pri_name varchar(20),
    pri_contact varchar(13),
    teach_name varchar(20),
    teach_contact varchar(13),
    award1 varchar(20),
    award2 varchar(20),
    attachment varchar(100),
    status int default '0'
)default charset=utf8;
create table resets(
	id int primary key auto_increment not null,
    userId int not null,
    token varchar(40),
    token_exptime int
)default charset=utf8;
insert into admins(name,password) values('admin','admin');