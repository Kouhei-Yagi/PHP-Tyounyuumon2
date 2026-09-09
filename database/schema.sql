drop database if exists shop;
create database shop default character set utf8 collate utf8_general_ci;

drop user if exists 'staff'@'localhost';
create user 'staff'@'localhost' identified by 'password';
grant all on shop.* to 'staff'@'localhost';

use shop;

create table product (
    id int auto_increment primary key,
    name varchar(200) not null,
    price int not null
);

create table customer (
    id int auto_increment primary key,
    name varchar(100) not null,
    address varchar(200) not null,
    login varchar(100) not null unique,
    password varchar(255) not null,
    created_at timestamp default current_timestamp
);

create table cart (
    customer_id int not null,
    product_id int not null,
    count int not null,
    primary key(customer_id, product_id),
    foreign key(customer_id) references customer(id),
    foreign key(product_id) references product(id)
);

create table purchase (
    id int auto_increment primary key,
    customer_id int not null,
    created_at timestamp default current_timestamp,
    foreign key(customer_id) references customer(id)
);

create table purchase_detail (
    purchase_id int not null,
    product_id int not null,
    count int not null,
    primary key(purchase_id, product_id),
    foreign key(purchase_id) references purchase(id),
    foreign key(product_id) references product(id)
);

create table favorite (
    customer_id int not null,
    product_id int not null,
    primary key(customer_id, product_id),
    foreign key(customer_id) references customer(id),
    foreign key(product_id) references product(id)
);

insert into product values(null, '松の実', 700);
insert into product values(null, 'くるみ', 270);
insert into product values(null, 'ひまわりの種', 210);
insert into product values(null, 'アーモンド', 220);
insert into product values(null, 'カシューナッツ', 250);
insert into product values(null, 'ジャイアントコーン', 180);
insert into product values(null, 'ピスタチオ', 310);
insert into product values(null, 'マカダミアナッツ', 600);
insert into product values(null, 'かぼちゃの種', 180);
insert into product values(null, 'ピーナッツ', 150);
insert into product values(null, 'クコの実', 400);
