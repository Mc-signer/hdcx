use hdcx;
select * from users;
select * from games;
select * from t_users;
select * from teams;
select * from members;
select * from resets;
select * from admins;
insert into admins(name,password,insititute,contact,degree) values('admin',SHA('123456'),'计算机系','18730267562','1');
update admins set password=SHA('admin') where id = '1';
alter table teams add column user_id int not null after id;
alter table members add column age int after gender;
insert into t_users(name,password,contact,status,email) values("msnmsg",SHA("123456"),"18730267562","1","651738022@qq.com");
delete from games where id = '4';
select id from teams order by id desc limit 1;
select * from games where games.deadline > "2015-01-02 15:00:00" and not exists(select * from teams where teams.game_id=games.id and teams.user_id = '1');
select games.name as gameName,users.name,teams.* from teams inner join users inner join games where teams.game_id = '1' and teams.user_id = users.id and games.id = '1';
select * from members where team_id = '1';
drop database hdcx;