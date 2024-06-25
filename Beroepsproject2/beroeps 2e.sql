CREATE DATABASE Softwareshop;
use Softwareshop;
 CREATE TABLE producten (
 producten_id int not null,
 naam varchar (255) not null,
 prijs decimal not null,
 beschrijving varchar(255) ,
primary key(producten_id)
 );
 
 create table klanten(
 klant_id int not null auto_increment,
 voornaam varchar (255) not null,
 achternaam varchar (255) not null,
 email varchar (255) not null,
 adres varchar (255) not null,
 telefoonnummer varchar(255),
 wachtwoord binary (64) not null,
 primary key(klant_id)
 );
 
 create table bestellingen(
 bestellingen_id int not null,
 producten_id int not null,
 klant_id int not null,
 beschrijving varchar(255),
 datum date not null,
 primary key (bestellingen_id),
 FOREIGN KEY ( producten_id) REFERENCES producten( producten_id),
FOREIGN KEY ( klant_id) REFERENCES klanten( klant_id)
 );
 
CREATE TABLE user_cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    product_id INT NOT NULL,
    FOREIGN KEY (id) REFERENCES klanten(klant_id),
    FOREIGN KEY (product_id) REFERENCES producten(producten_id)
);
 
 