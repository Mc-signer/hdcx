use hdcx;
alter table users add column xingming varchar(10) not null after name;
alter table t_users add column xingming varchar(10) not null after name;
alter table teams drop column award;
alter table members drop column age;
alter table teams add column award1 varchar(20) default '无' after teach_contact ;
alter table teams add column award2 varchar(20) default '无' after teach_contact ;
alter table teams alter column attachment set default '否';