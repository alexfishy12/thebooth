use store_template;

select * from Customer;

-- '1', 'Test', 'Person', 'healylia@kean.edu', '1234', '54 Test Ave', 'Metuchen', 'NJ', '08840', '0000-00-00 00:00:00';

delete from Customer where email = 'fisheral@kean.edu';

delete from Customer_Image where customer_id = 5;

drop table Customer_Image;
drop table Customer;

select * from Customer;

select * from the_booth.Admin;

select * from Employee;

select * from Size;
insert into store_template.Size (Size) values ('XS'), ('S'), ('M'), ('L'), ('XL'), ('2XL');
insert into store_template.Color (Color) values ('red'), ('blue'), ('green'), ('white'), ('black'), ('gray');
insert into store_template.Category (category) values ('shirt'), ('pants'), ('jacket'), ('sweater');

alter table Product_Image ADD COLUMN color_id int not null;

ALTER TABLE Product_Image
ADD CONSTRAINT fk_color
FOREIGN KEY (color_id) REFERENCES Color(id);

select * from Product_Image;

select * from Color;

select * from Product;

select * from Product_Image pi left join Color c on (pi.color_id = c.id) where product_id = 4;
desc Product;
ALTER TABLE Product
ADD COLUMN created DATETIME;