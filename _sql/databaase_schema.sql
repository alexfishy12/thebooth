create database the_booth;
use the_booth;


create table Account (
    id int primary key not null auto_increment,
    email varchar(255) UNIQUE not null,
    first_name varchar(255) not null,
    last_name varchar(255) not null,
    password varchar(255) not null,
    salt binary(16) not null,
    type char(1) not null,
    store_name varchar(255),
    created datetime not null
);


-- The admin account will be the only account that can create new store accounts.
insert into Account (email, first_name, last_name, password, store_name, created) values 
('fisheral@kean.edu', 'Alexander', 'Fisher', 'abc123', 'Nike', now());

insert into Account_Type (id, type) values (LAST_INSERT_ID(), 'A');


-- Each store that buys our software will have their own database. The database name will be the store's name.
-- Each database instance will be identical in structure. The only difference will be the data inside.


create database store_template;
use store_template;


create table Category (
    id int primary key not null auto_increment,
    category varchar(255) not null
);


create table Color (
    id int primary key not null auto_increment,
    color varchar(255) not null
);


create table Size (
    id int primary key not null auto_increment,
    size varchar(255) not null
);


create table Product (
    id int primary key not null auto_increment,
    name varchar(255) not null,
    description varchar(255) not null,
    price decimal(10,2) not null,
    quantity int not null
);


create table Product_Image (
    id int primary key not null auto_increment,
    product_id int not null,
    image_og blob not null,
    image_pp blob not null,
    foreign key (product_id) references Product(id)
);


create table Product_Size (
    id int primary key not null auto_increment,
    product_id int not null,
    size_id int not null,
    foreign key (product_id) references Product(id),
    foreign key (size_id) references Size(id)
);


create table Product_Color (
    id int primary key not null auto_increment,
    product_id int not null,
    color_id int not null,
    foreign key (product_id) references Product(id),
    foreign key (color_id) references Color(id)
);


create table Product_Category (
    id int primary key not null auto_increment,
    product_id int not null,
    category_id int not null,
    foreign key (product_id) references Product(id),
    foreign key (category_id) references Category(id)
);


create table Customer (
    id int primary key not null auto_increment,
    first_name varchar(255) not null,
    last_name varchar(255) not null,
    email varchar(255) UNIQUE not null,
    password varchar(255) not null,
    salt binary(64) not null,
    address varchar(255) not null,
    city varchar(255) not null,
    state varchar(255) not null,
    zip varchar(255) not null,
    created datetime not null
);


create table Customer_Image (
    id int primary key not null auto_increment,
    customer_id int not null,
    image_og blob not null,
    image_pp blob not null,
    foreign key (customer_id) references Customer(id)
);


create table `Order` (
    id int primary key not null auto_increment,
    customer_id int not null,
    date datetime not null,
    status varchar(255) not null,
    foreign key (customer_id) references Customer(id)
);


create table Product_Order (
    id int primary key not null auto_increment,
    order_id int not null,
    product_id int not null,
    quantity int not null,
    foreign key (order_id) references `Order`(id),
    foreign key (product_id) references Product(id)
);


create table Review (
    id int primary key not null auto_increment,
    product_id int not null,
    customer_id int not null,
    rating int not null,
    review varchar(255) not null,
    date datetime not null,
    foreign key (product_id) references Product(id),
    foreign key (customer_id) references Customer(id)
);