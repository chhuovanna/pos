set global default_storage_engine=innodb;


create database mnpos character set = utf8 collate = utf8_general_ci;



USE mnpos;




create table products(pid int not null auto_increment primary key,name varchar(100) unique,barcode bigint unique, description text, shortcut varchar(50), salepriceunit decimal(8,2), salepricepack decimal(8,2), salepricebox decimal(8,2),
	photopath varchar(255), unitinstock int, packintstock int, boxinstock int,unitperpack int, unitperbox int, mid int, catid int, isdrugs tinyint/*1:true, 0 false*/);
	
create table categories(catid int not null auto_increment primary key, name varchar(100) unique);
	
create table manufacturers(mid int not null auto_increment primary key, name varchar(50) unique, address varchar(256), tel varchar(50));

create table importers(impid int not null auto_increment primary key, name varchar(50) unique, address varchar(256), tel varchar(50), email varchar(50));

create table saleassistants(said int not null auto_increment primary key, name varchar(50), address varchar(256), tel varchar(50), email varchar(50) , impid int);

create table customers(cusid int not null auto_increment primary key, name varchar(50), address varchar(256), tel varchar(50), email varchar(50));

create table exchangerates(exrateid int not null auto_increment primary key, currentrate tinyint, amount decimal(4,0)); /*4010*/

create table drugs (pid int primary key, activesub varchar(255) , leafletpath varchar(255), leaftleturl varchar(255), size varchar(100) /* 100mg, 60ml*/ ,
	dousageform varchar(100) /*tablet or drop...*/);

create table productimportersaleassistants(pid int, impid int, said int, primary key (pid,impid,said));

create table inventories(invid bigint not null auto_increment primary key, pid int, impid int, mfg date /*manufacture date*/, exp date/*expired date*/, importunit int, importpack int, importbox int, importdate date, 
	SRP decimal(8,2), buypriceunit decimal(8,2),buypricepack decimal(8,2),buypricebox decimal(8,2), unitinstock int,packinstock int,boxinstock int, finish tinyint /*1:true, 0 false*/, shelf varchar(100), amount decimal(8,2) /*total amount of money*/);

create table sales (saleid bigint not null auto_increment primary key, exchangerate decimal(8,2), saledate datetime default current_timestamp, cusid int, total decimal(8,2), discount decimal(5,2) /*percentage, e.g. 5.5 = 5.5%*/, ftotal decimal(8,2), recievedd decimal(8,2), recievedr decimal(8,2), comment varchar(255) );

create table saleproducts(saleid bigint, pid int, unitquantity int, packquantity int, boxquantity int, subtotal decimal(8,2), salepriceunit decimal(8,2),  salepricepack decimal(8,2),
 salepricebox decimal(8,2),  stock varchar(100), primary key (saleid, pid));

alter table products add foreign key (mid) references manufacturers(mid) on update cascade, add foreign key (catid) references categories(catid) on update cascade;

alter table drugs add foreign key (pid) references products(pid);

alter table productimportersaleassistants add foreign key (pid) references products(pid), add foreign key (impid) references importers(impid), 
	add foreign key (said) references saleassistants(said) ; 
    
    
alter table inventories add foreign key (pid) references products(pid), add foreign key (impid) references importers(impid) on update cascade;

alter table sales add foreign key (cusid) references customers(cusid);


alter table saleproducts add foreign key (saleid) references sales(saleid) on delete cascade, add foreign key (pid) references products(pid) on delete cascade;

alter table saleassistants add foreign key (impid) references importers(impid);



alter table products add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table categories add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table customers add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table drugs add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table exchangerates add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table importers add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table manufacturers add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table productimportersaleassistants add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table saleassistants add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table inventories add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table sales add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table saleproducts add created_at timestamp default current_timestamp, add updated_at timestamp default current_timestamp;
alter table products modify name varchar(100) unique;
alter table categories modify name varchar(100) unique;
alter table manufacturers modify name varchar(100) unique;
alter table importers modify name varchar(100) unique;


INSERT INTO `categories` VALUES (-1,'Unknown',null,null),(1,'Snack','2017-08-18 10:32:27','2017-08-18 10:32:27'),(2,'Drink','2017-08-18 10:32:27','2017-08-18 10:32:27'),(3,'Cosmetic','2017-08-18 10:32:27','2017-08-18 10:32:27'),(4,'soap','2017-08-18 10:32:27','2017-08-18 10:32:27'),(5,'shampoo','2017-08-18 10:32:27','2017-08-18 10:32:27'),(6,'cloth','2017-08-19 01:51:29','2017-08-19 01:51:29'),(12,'electronic','2017-08-20 09:48:36','2017-08-20 09:48:36'),(13,'whitening cream','2017-08-22 01:58:04','2017-08-22 01:58:04');
INSERT INTO `importers` VALUES (-1,'Unknown',null,null,null,null,null),(1,'uniliver','asdfsd',NULL,NULL,'2017-08-18 10:35:02','2017-08-18 10:35:02'),(2,'dksl','asdfsd',NULL,NULL,'2017-08-18 10:35:02','2017-08-18 10:35:02'),(3,'k4','asdfsd',NULL,NULL,'2017-08-18 10:35:02','2017-08-18 10:35:02'),(4,'lgear','asdfsd',NULL,NULL,'2017-08-18 10:35:02','2017-08-18 10:35:02');
INSERT INTO `manufacturers` VALUES (-1,'Unknown',null,null,null,null,null),(1,'lg','Preah Monivong Blvd (93), Phnom Penh',NULL,'2017-08-18 10:36:53','2017-08-22 02:09:25'),(2,'asus',NULL,NULL,'2017-08-18 10:36:53','2017-08-18 10:36:53'),(3,'hartari',NULL,NULL,'2017-08-18 10:36:53','2017-08-18 10:36:53'),(4,'samsung',NULL,NULL,'2017-08-18 10:36:53','2017-08-18 10:36:53');
INSERT INTO customers values(-1,"Expired",null,null,null, null,null ),(-2,"Lost",null, null,null, null,null),(-3,"Used",null, null,null, null,null),(-4,"Gift",null, null,null, null,null),(-5,"Return",null, null,null, null,null);



#prevent deletion of current exchange rate
DELIMITER //
CREATE TRIGGER reinsertcurrentexc
AFTER DELETE ON exchangerates
FOR EACH ROW
IF old.currentrate= 1 THEN
    INSERT INTO exchangerates
    VALUES (old.exrateid, old.amount,old.created_at, old.updated_at, old.currentrate);
END IF;
//

DELIMITER ;



#modify for rtpos

alter table products modify barcode bigint unique null;

alter table products add importpriceunit decimal(8,2), add importpricepack decimal(8,2), add importpricebox decimal(8,2);

alter table customers add tel1 varchar(50), add tel2 varchar(50);
alter table importers add tel1 varchar(50), add tel2 varchar(50);
alter table manufacturers add tel1 varchar(50), add tel2 varchar(50);
alter table saleassistants add tel1 varchar(50), add tel2 varchar(50);

create table stockoutType(sotid int primary key, type varchar(20)) engine = Innodb;
insert into stockoutType values(1,"Sale"),(2,"Sale with loan"), (5, "Return"),(6,"Expired"), (7,"Lost") , (8,"Used") ,(9,"Gift");

INSERT INTO customers(cusid,name) values(1,"General");
#delete return gift... use stockout type instead.
delete from customers where cusid < 0;


#add fk stockouttype in sale
alter table sales add sotid int(11);
alter table sales add foreign key (sotid) references stockoutType (sotid);


#create table loan
create table loan (saleid bigint(20), amount decimal(8,2) , state smallint /*0: not yet clear, 1: is cleared*/, created_at timestamp default current_timestamp, updated_at timestamp default current_timestamp, primary key (saleid),  foreign key (saleid) references sales(saleid) on delete cascade) engine=innodb;

#alter discount .xxxx
alter table sales modify discount decimal(7,4);

#alter for win product prize
update stockouttype set sotid = 3,  type = 'Prize' where sotid = 9;

#alter table for .xxxx 
alter table inventories modify amount decimal(10,4)
	, modify buypriceunit decimal(10,4)
    , modify buypricepack decimal(10,4)
	, modify buypricebox decimal(10,4);

alter table loan modify amount decimal (10,4);

alter table products modify salepriceunit decimal(10,4)
	, modify salepricepack decimal(10,4)
    , modify salepricebox decimal(10,4)
    , modify importpricepack decimal(10,4)
	, modify importpriceunit decimal(10,4)
    , modify importpricebox decimal(10,4)
    ;
    
alter table saleproducts modify subtotal decimal(10,4)
	, modify salepriceunit decimal(10,4)
	, modify salepricepack decimal(10,4)
    , modify salepricebox decimal(10,4)
    ;
alter table sales modify total decimal(10,4)
	, modify ftotal decimal(10,4)
    ;

#create table winmoneyprize and winmoneyprizeproduct
create table winmoneyprize (wmpid bigint auto_increment primary key
	, cusid int
    , created_at timestamp default current_timestamp
    , updated_at timestamp default current_timestamp
    , paytotalr decimal(8,0)
    , wintotalr decimal(8,0)
    , lefttotalr decimal(8,0)
    
    ) engine = InnoDb;
alter table winmoneyprize add foreign key (cusid) references customers(cusid);


create table winmoneyprizeproduct (wmpid bigint
	, pid int
    , created_at timestamp default current_timestamp
    , updated_at timestamp default current_timestamp
    , payamountr decimal(8,0)
    , winamountr decimal(8,0)
    , unit int
    , paysubtotalr decimal(8,0)
    , winsubtotalr decimal(8,0)
    , leftsubtoatr decimal(8,0)
    , primary key (wmpid, pid)
    ) engine = InnoDB;
   
alter table winmoneyprizeproduct add foreign key (wmpid) references winmoneyprize(wmpid);

#alter table win money in dollar

alter table winmoneyprize change paytotalr paytotal decimal(10,4);
alter table winmoneyprize change wintotalr wintotal decimal(10,4);
alter table winmoneyprize change lefttotalr lefttotal decimal(10,4);

alter table winmoneyprizeproduct change payamountr payamount decimal(10,4);
alter table winmoneyprizeproduct change winamountr winamount decimal(10,4);
alter table winmoneyprizeproduct change paysubtotalr paysubtotal decimal(10,4);
alter table winmoneyprizeproduct change winsubtotalr winsubtotal decimal(10,4);
alter table winmoneyprizeproduct change leftsubtoatr leftsubtotal decimal(10,4);

use rtpos;
use mnpos1;
#alter table winmoneyprizeproduct delete cascade

alter table winmoneyprizeproduct drop foreign key winmoneyprizeproduct_ibfk_2;
alter table winmoneyprizeproduct add foreign key winmoneyprizeproduct_ibfk_1 (wmpid) references
winmoneyprize(wmpid) on delete cascade;

#add exchange rate to win moneyprize
alter table winmoneyprize add exchangerate decimal(10);

#temporary

alter table products modify barcode bigint;


##add menu
#stock reminder http://rtpos/admin/product/stockreminder
#win money http://rtpos/admin/winmoneyprize/list
#loan http://rtpos/admin/loan

#use to check stock in p and iv
select p.pid, p.unitinstock, p.packinstock, p.boxinstock, i.invid
from products p left join  inventories i
	using(pid)
where not isnull(p.unitinstock);

select *
from products p left join  inventories i
	using(pid)
where pid = 3;



#change table name to all lower case
rename table stockoutType	to stockouttype;

use mnpos1;
use rtpos;


#remove .xxx for riel
alter table sales modify recievedr decimal(8,0);
alter table sales modify recievedd decimal(10,4);
alter table sales modify exchangerate decimal(4,0);

alter table winmoneyprize modify exchangerate decimal(4,0);



#create view for stockreminder grid
create view stockreminder as 

select p.pid
	, p.name
	, p.unitinstock
	, p.packinstock
	, p.boxinstock
	, p.unitperpack
	, p.unitperbox
	, (p.unitinstock + (p.packinstock*p.unitperpack) + (p.boxinstock*p.unitperbox)) as totalunitinstock
    
    , (p.unitinstock + (p.packinstock*p.unitperpack) + (p.boxinstock*p.unitperbox))/p.unitperbox as totalboxinstock
    , im.impid as impid
    , im.name as importer
    , inv.buypricebox buypricebox
    , p.catid
    , p.barcode
    , p.description
    , p.shortcut
from products p 
    left join (inventories inv 
                join (select max(invid) latestinvid from inventories inv group by pid) as temp 
                join importers im 
                on temp.latestinvid = inv.invid and inv.impid = im.impid )
    on  inv.pid = p.pid 
order by importer, totalboxinstock;
select * from stockreminder;

##change quantity with .x

alter table inventories modify importpack decimal(5,1)
, modify importbox decimal(5,1)
, modify packinstock decimal(5,1)
, modify boxinstock decimal(5,1)
;


alter table saleproducts modify packquantity decimal(5,1)
, modify boxquantity decimal(5,1)
;


#add avgbuyprice box for expense calculation
alter table saleproducts add avgbuypriceunit decimal(10,4);
alter table inventories add avgbuypriceunit decimal(10,4);



  
    
# update avg price unit for the past


Delimiter //

create procedure set_avg_buy_price_unit_finished_inv()
begin
DECLARE finished_var INTEGER DEFAULT 0;
declare pid_var int;
declare avg_buy_price_unit_var decimal(10,4);
declare pids_cursor cursor for
	select distinct pid 
    from inventories 
    where finish = 1;    
#to assure when cursor reach the end of result
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished_var = 1;

open pids_cursor;
get_pids: loop
	fetch pids_cursor into pid_var;
    
    if finished_var = 1 then 
		leave get_pids;
	end if;
    
    
     select 
	(select  sum( (inv.importunit*inv.buypriceunit) 
		+ (inv.importpack*inv.buypricepack) 
		+ (inv.importbox*inv.buypricebox)) as totalcost
		
	from inventories inv
	where finish = 1 and pid = pid_var) / 
	(select sum( inv.importunit 
		+ (inv.importpack*unitperpack) 
		+ (inv.importbox*unitperbox) ) as totalunit
	from products p
		join inventories inv 
        on p.pid = inv.pid
	where p.pid = pid_var and finish = 1) into avg_buy_price_unit_var;
    
    update inventories set avgbuypriceunit = avg_buy_price_unit_var where pid = pid_var and finish = 1;
    
    update saleproducts set avgbuypriceunit = avg_buy_price_unit_var where pid = pid_var;
    
end loop;
close pids_cursor;
end//

Delimiter ;
call set_avg_buy_price_unit_finished_inv();

#procedure of not finish invent



Delimiter //

create procedure set_avg_buy_price_unit_not_finished_inv()
begin
DECLARE finished_var INTEGER DEFAULT 0;
declare pid_var int;
declare avg_buy_price_unit_var decimal(10,4);
declare pids_cursor cursor for
	select distinct pid 
    from inventories 
    where finish = 0;    
#to assure when cursor reach the end of result
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished_var = 1;

open pids_cursor;
get_pids: loop
	fetch pids_cursor into pid_var;
    
    if finished_var = 1 then 
		leave get_pids;
	end if;
    
    
     select 
    (select  sum( (inv.unitinstock*inv.buypriceunit) 
        + (inv.packinstock*inv.buypricepack) 
        + (inv.boxinstock*inv.buypricebox)) as totalcost
        
    from inventories inv
    where finish = 0 and pid = pid_var) / 
    (select unitinstock 
        + (packinstock*unitperpack) 
        + (boxinstock*unitperbox) as totalunit
    from products 
    where pid = pid_var) into avg_buy_price_unit_var;
    
    update inventories set avgbuypriceunit = avg_buy_price_unit_var where pid = pid_var and finish = 0;
    
    update saleproducts set avgbuypriceunit = avg_buy_price_unit_var where pid = pid_var;
    
end loop;
close pids_cursor;
end//

Delimiter ;

call  set_avg_buy_price_unit_not_finished_inv();





#get avgpriceunit of finish inve


    select importunit, importpack, importbox
		, buypriceunit, buypricepack
        , buypricebox
        , unitperpack
        , unitperbox
        , importunit 
		+ (importpack*unitperpack) 
		+ (importbox*unitperbox) as totalunit
        , (importunit*buypriceunit) 
		+ (importpack*buypricepack) 
		+ (importbox*buypricebox) as totalcost
    from products p
		join inventories inv 
        on p.pid = inv.pid
	where p.pid = 353 and finish = 0;
    
    select sum((importunit*buypriceunit) 
		+ (importpack*buypricepack) 
		+ (importbox*buypricebox) )/sum( importunit 
		+ (importpack*unitperpack) 
		+ (importbox*unitperbox) )  as avgprice
        
    from products p
		join inventories inv 
        on p.pid = inv.pid
	where p.pid = 353 and finish = 0;
    
    
  
  
  
  


    select inv.unitinstock, inv.packinstock, inv.boxinstock
		, buypriceunit, buypricepack
        , buypricebox
        , unitperpack
        , unitperbox
        , inv.unitinstock 
		+ (inv.packinstock*unitperpack) 
		+ (inv.boxinstock*unitperbox) as totalunit
        , (inv.unitinstock*buypriceunit) 
		+ (inv.packinstock*buypricepack) 
		+ (inv.boxinstock*buypricebox) as totalcost
    from products p
		join inventories inv 
        on p.pid = inv.pid
	where p.pid = 353 and finish = 0;
    
    select sum((inv.unitinstock*buypriceunit) 
		+ (inv.packinstock*buypricepack) 
		+ (inv.boxinstock*buypricebox)  )/sum( inv.unitinstock 
		+ (inv.packinstock*unitperpack) 
		+ (inv.boxinstock*unitperbox) )  as avgprice
        
    from products p
		join inventories inv 
        on p.pid = inv.pid
	where p.pid = 353 and finish = 0;  


#get average price 
select 
	(select  sum( (inv.unitinstock*inv.buypriceunit) 
		+ (inv.packinstock*inv.buypricepack) 
		+ (inv.boxinstock*inv.buypricebox)) as totalcost
		
	from products p 
		join inventories inv
		on p.pid = inv.pid
	where finish = 0 and p.pid = 179) / 
	(select unitinstock 
		+ (packinstock*unitperpack) 
		+ (boxinstock*unitperbox) as totalunit
	from products 
	where pid = 179) as avgbuypriceunit
;



select 
	(	(select sum((inv.unitinstock 
			+ (inv.packinstock*unitperpack) 
			+ (inv.boxinstock*unitperbox))*avgbuypriceunit)
		from products p
			join inventories inv 
			on p.pid = inv.pid
		where p.pid = 353 and finish = 0 and !isnull(avgbuypriceunit)
		)
    +
		(select sum((inv.unitinstock*buypriceunit) 
			+ (inv.packinstock*buypricepack) 
			+ (inv.boxinstock*buypricebox)  ) as totalcost
		from inventories inv
		where pid = 353 and finish = 0 and isnull(avgbuypriceunit)
		)
	)/
	(select sum(inv.unitinstock 
		+ (inv.packinstock*unitperpack) 
		+ (inv.boxinstock*unitperbox))
	from products p
		join inventories inv 
		on p.pid = inv.pid
	where p.pid = 353 and finish = 0 
    ) AS new_avg_price;



#create table for unit based on category
create table categoryunitname (catid int primary key, packname varchar(100), boxname varchar(100)
	, foreign key (catid) references categories(catid));
 
 use mnpos1;
 #change pack and box instock to decimal
 alter table products modify packinstock decimal(5,1)
	, modify boxinstock decimal(5,1);


