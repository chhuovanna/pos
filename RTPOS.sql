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

