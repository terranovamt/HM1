
 create database expo;
 use expo;

 create table zona(
    id integer primary key
) engine = 'InnoDB'; 

 create table padiglione(
    nome varchar(32) PRIMARY KEY,
    dimensione float
) engine = 'InnoDB';
 
 create table modello(
	 nome varchar(32) primary key,
	 img varchar(32),
	 descrizione varchar(255)
 )engine = 'InnoDB';

 create table stand(
	 codice integer PRIMARY KEY,
	 tipo BOOLEAN
 )engine ='InnoDB';

 create table standinterno(
    codice integer PRIMARY KEY, 
    numero integer,
    dimensione FLOAT,
    disponibilita BOOLEAN default 0,
    posizione varchar(32),
	modello varchar(32),
	INDEX nomemodello(modello),
    INDEX nomepadilgione(posizione),
	INDEX codicestand(codice),
    FOREIGN KEY(modello) REFERENCES modello(nome),
    FOREIGN KEY(posizione) REFERENCES padiglione(nome),
	FOREIGN KEY(codice) REFERENCES stand(codice)
) engine = 'InnoDB';

 create table standesterno(
    codice integer PRIMARY KEY ,
    numero integer,
    dimensione float default 500,
    disponibilita BOOLEAN default 0,
    posizione integer,
    INDEX numerozona(posizione),
	INDEX codicestand(codice),
    FOREIGN KEY(posizione) REFERENCES zona(id),
	FOREIGN KEY(codice) REFERENCES stand(codice)
) engine = 'InnoDB';

 create table users (
    id integer primary key auto_increment,
    username varchar(32) not null unique,
    password varchar(255) not null,
    email varchar(64) not null unique,
    name varchar(32) not null,
    surname varchar(32) not null
) engine = 'InnoDB';

 create table locazioneinterno(
	stand integer, 
	azienda integer,
	INDEX codicestand(stand),
	INDEX nomeazienda(azienda),
	FOREIGN KEY(stand) REFERENCES standinterno(codice),
	FOREIGN KEY(azienda) REFERENCES users(id),
	PRIMARY KEY(stand,azienda)
 ) engine = 'InnoDB'; 

 create table locazionesterno( 
 	stand integer, 
	azienda integer,
	INDEX codicestand(stand),
	INDEX nomeazienda(azienda),
	FOREIGN KEY(stand) REFERENCES standesterno(codice),
	FOREIGN KEY(azienda) REFERENCES users(id),
	PRIMARY KEY(stand,azienda)
) engine = 'InnoDB'; 

 create table evento(
    id integer primary key auto_increment,
 	nome varchar(32),
 	img varchar(32),
	edizione integer,
   	durata integer,
	prezzo float,
   	data_inizio DATE,
	data_fine DATE
) engine = 'InnoDB'; 

 create table partecipa(
 	evento integer,
   	azienda integer,
	stand integer,
	costo float,
	INDEX id_evento(evento),
	INDEX id_azienda(azienda),
	INDEX codicestand(stand),
	FOREIGN KEY(evento) REFERENCES evento(id),	
	FOREIGN KEY(azienda) REFERENCES users(id),
	FOREIGN KEY(stand) REFERENCES stand(codice),
	PRIMARY KEY(evento, azienda, stand)

) engine = 'InnoDB';

 create table sponsor(
	nome varchar(32) PRIMARY KEY
) engine = 'InnoDB';

 create table prodotto(
    codice integer PRIMARY KEY,
    nome varchar(32),
	categoria varchar(32)
) engine = 'InnoDB';

 create table espone(
	prodotto integer,
   	azienda integer,
	sponsor varchar(32),
	INDEX codice_prodotto(prodotto),
	INDEX nome_azienda(azienda),
	INDEX nome_sponsor(sponsor),
	FOREIGN KEY(prodotto) REFERENCES PRODOTTO(codice),	
	FOREIGN KEY(azienda) REFERENCES users(id),
	FOREIGN KEY(sponsor) REFERENCES SPONSOR(nome),
	PRIMARY KEY(prodotto, azienda, sponsor)
) engine = 'InnoDB';


DELIMITER  //
create trigger inserisci_durata
before insert 
on evento
for each row 
begin 
set new.durata = DATEDIFF(new.data_fine,new.data_inizio);
end //
DELIMITER  ;

DELIMITER  //
create trigger inserisci_costo
before insert 
on partecipa
for each row 
begin 
set new.costo = (select durata from evento WHERE new.evento = id) * (select prezzo from evento WHERE new.evento = id);
end //
DELIMITER  ;

DELIMITER  //
create trigger disponibilita_esterno
after insert 
on locazionesterno
for each row 
begin 
update standesterno set disponibilita = 1 
where  codice = new.stand;
end //
DELIMITER  ;

DELIMITER  //
create trigger delete_disponibilita_esterno
after delete 
on locazionesterno
for each row 
begin 
update standesterno set disponibilita = 0 
where  codice = old.stand;
end //
DELIMITER  ;

DELIMITER  //
create trigger disponibilita_interno
after insert 
on locazioneinterno
for each row 
begin 
update standinterno set disponibilita = 1 
where  codice =new.stand;
end //
DELIMITER  ;

DELIMITER  //
create trigger delete_disponibilita_interno
after delete 
on locazioneinterno
for each row 
begin 
update standinterno set disponibilita = 0 
where  codice =old.stand;
end //
DELIMITER  ;


-- drop VIEW esterno_liberi;
CREATE VIEW esterno_liberi as 
SELECT * FROM standesterno WHERE disponibilita = 0;

-- drop VIEW interno_liberi;
CREATE VIEW interno_liberi as 
SELECT * FROM standinterno WHERE disponibilita = 0;

-- drop VIEW esterno_occupati;
CREATE VIEW esterno_occupati as 
SELECT * FROM standesterno WHERE disponibilita = 1;

-- drop VIEW interno_occupati;
CREATE VIEW interno_occupati as 
SELECT * FROM standinterno WHERE disponibilita = 1;


insert into padiglione (nome, dimensione)
values
 ('A',10000),
 ('B',11000),
 ('C',12000),
 ('D',13000),
 ('E',14000),
 ('F',15000),
 ('G',16000);


insert into zona (id)
values 
 (1),
 (2), 
 (3),
 (4),
 (5);

insert into modello ( nome, img, descrizione) 
values
	( "Klover","../img/1_1.jpg","Stand al chiuso di medie dimensioni senza pareti e tetto aperto, illuminazione con fari da incasso, ottimo per esporre complementi di arredo. "),
	( "Efi", "../img/1_2.jpg", "Stand al chiuso di grandi dimensioni con americane a vista, illuminazione con faretti, ottimo per autobomili o arredamenti. "),
    ( "Mistral", "../img/1_3.jpg", "Stand al chiuso di piccole dimensioni con pareti rivestite di grafica, pavimento laccato lucido, ottimo per gioiellerie, orologerie e piccolo oggetti. "),
    ( "Aico", "../img/1_4.jpg", "Stand al chiuso di grandi dimensioni con pavimento laccato lucido, ottimo per offrire un servisio alle persone, ambienti dedicati al relax o al ristoro. "),
    ( "Bakoo", "../img/1_5.jpg", "Stand al chiuso di medie dimensioni con grafiche luminose, illuminazione con fari da incasso, soffitto sospeso.  "),
    ( "Blit", "../img/1_6.jpg", "Stand al chiuso di medie dimensioni con Ledwall:Videowall, illuminazione con fari da incasso. "),
    ( "Caster", "../img/1_7.jpg", "Stand al chiuso di piccole dimensioni con pareti rivestite di grafica, pavimento laccato lucido. "),
    ( "Tender", "../img/1_8.jpg", "Stand al chiuso di piccole dimensioni con pareti in legno, pavimento in legno. "),
    ( "Green", "../img/1_9.jpg", "Stand al chiuso di medie dimensioni con pavimento in moquette, ottimo per esporre fiori e piante per arredare ambienti interni. ");
 
insert into stand 
values
 (1001,1),
 (1002,1),
 (1003,1),
 (1004,1),
 (1005,1),
 (1006,1),
 (2001,1),
 (2002,1),
 (2003,1),
 (2004,1),
 (2005,1),
 (2006,1),
 (3001,1),
 (3002,1),
 (3003,1),
 (3004,1),
 (3005,1),
 (3006,1),
 (4001,1),
 (4002,1),
 (4003,1),
 (4004,1),
 (4005,1),
 (4006,1),
 (5001,1),
 (5002,1),
 (5003,1),
 (5004,1),
 (5005,1),
 (5006,1),
 (6001,1),
 (6002,1),
 (6003,1),
 (6004,1),
 (6005,1),
 (6006,1),
 (7001,1),
 (7002,1),
 (7003,1),
 (7004,1),
 (7005,1),
 (7006,1),
 (8001,0),
 (8002,0),
 (8003,0),
 (8004,0),
 (8005,0),
 (8006,0),
 (9001,0),
 (9002,0),
 (9003,0),
 (9004,0),
 (9005,0),
 (9006,0),
 (10001,0),
 (10002,0),
 (10003,0),
 (10004,0),
 (10005,0),
 (10006,0),
 (11001,0),
 (11002,0),
 (11003,0),
 (11004,0),
 (11005,0),
 (11006,0),
 (12001,0),
 (12002,0),
 (12003,0),
 (12004,0),
 (12005,0),
 (12006,0);

insert into standinterno (codice, numero, dimensione, posizione, modello)
values 
 (1001, 1, 101, 'A',"Klover"),
 (1002, 2, 132, 'A',"Efi"),
 (1003, 3, 143, 'A',"Mistral"),
 (1004, 4, 146, 'A',"Aico"),
 (1005, 5, 345, 'A',"Bakoo"),
 (1006, 6, 468, 'A',"Blit"),
 (2001, 1, 634, 'B',"Caster"),
 (2002, 2, 697, 'B',"Tender"),
 (2003, 3, 524, 'B',"Green"),
 (2004, 4, 568, 'B',"Klover"),
 (2005, 5, 346, 'B',"Efi"),
 (2006, 6, 709, 'B',"Mistral"),
 (3001, 1, 269, 'C',"Aico"),
 (3002, 2, 174, 'C',"Bakoo"),
 (3003, 3, 154, 'C',"Blit"),
 (3004, 4, 475, 'C',"Caster"),
 (3005, 5, 174, 'C',"Tender"),
 (3006, 6, 341, 'C',"Green"),
 (4001, 1, 101, 'D',"Klover"),
 (4002, 2, 132, 'D',"Efi"),
 (4003, 3, 143, 'D',"Mistral"),
 (4004, 4, 146, 'D',"Aico"),
 (4005, 5, 345, 'D',"Bakoo"),
 (4006, 6, 468, 'D',"Blit"),
 (5001, 1, 634, 'E',"Caster"),
 (5002, 2, 697, 'E',"Tender"),
 (5003, 3, 524, 'E',"Green"),
 (5004, 4, 568, 'E',"Klover"),
 (5005, 5, 346, 'E',"Efi"),
 (5006, 6, 709, 'E',"Mistral"),
 (6001, 1, 269, 'F',"Aico"),
 (6002, 2, 174, 'F',"Bakoo"),
 (6003, 3, 154, 'F',"Blit"),
 (6004, 4, 475, 'F',"Caster"),
 (6005, 5, 174, 'F',"Tender"),
 (6006, 6, 341, 'F',"Green"),
 (7001, 1, 634, 'G',"Klover"),
 (7002, 2, 697, 'G',"Efi"),
 (7003, 3, 524, 'G',"Mistral"),
 (7004, 4, 568, 'G',"Aico"),
 (7005, 5, 346, 'G',"Bakoo"),
 (7006, 6, 709, 'G',"Blit");
 
insert into standesterno (codice, numero, posizione)
values 
 (8001, 1, 1),
 (8002, 2, 1),
 (8003, 3, 1),
 (8004, 4, 1),
 (8005, 5, 1),
 (8006, 6, 1),
 (9001, 1, 2),
 (9002, 2, 2),
 (9003, 3, 2),
 (9004, 4, 2),
 (9005, 5, 2),
 (9006, 6, 2),
 (10001, 1, 3),
 (10002, 2, 3),
 (10003, 3, 3),
 (10004, 4, 3),
 (10005, 5, 3),
 (10006, 6, 3),
 (11001, 1, 4),
 (11002, 2, 4),
 (11003, 3, 4),
 (11004, 4, 4),
 (11005, 5, 4),
 (11006, 6, 4),
 (12001, 1, 5),
 (12002, 2, 5),
 (12003, 3, 5),
 (12004, 4, 5),
 (12005, 5, 5),
 (12006, 6, 5);


insert into evento(nome, edizione, data_inizio, data_fine ,prezzo, img)
values
	('Evento 1', 1, '2021-01-03', '2021-01-08',5,"./loghi/undefinited.png"),
	('Evento 1', 2, '2022-01-04', '2022-01-10',5,"./loghi/undefinited.png"),
	('Evento 1', 3, '2023-01-02', '2023-01-06',5,"./loghi/undefinited.png"),
	('Evento 2', 1, '2021-02-19', '2021-02-27',5,"./loghi/undefinited.png"),
	('Evento 2', 2, '2022-02-15', '2022-02-24',5,"./loghi/undefinited.png"),
	('Evento 2', 3, '2023-02-20', '2023-02-28',5,"./loghi/undefinited.png"),
	('Evento 3', 1, '2021-03-07', '2021-03-12',5,"./loghi/undefinited.png"),
	('Evento 3', 2, '2022-03-08', '2022-03-15',5,"./loghi/undefinited.png"),
	('Evento 3', 3, '2023-03-04', '2023-03-10',5,"./loghi/undefinited.png"),
	('Evento 4', 1, '2021-04-01', '2021-04-15',5,"./loghi/undefinited.png"),
	('Evento 4', 2, '2022-04-01', '2022-04-15',5,"./loghi/undefinited.png"),
	('Evento 4', 3, '2023-04-01', '2023-04-15',5,"./loghi/undefinited.png"),
	('Evento 5', 1, '2021-05-22', '2021-06-01',5,"./loghi/undefinited.png"),
	('Evento 5', 2, '2022-05-21', '2022-05-31',5,"./loghi/undefinited.png"),
	('Evento 5', 3, '2023-05-19', '2023-05-30',5,"./loghi/undefinited.png"),
    ('Evento 6', 1, '2021-06-01', '2021-06-05',5,"./loghi/undefinited.png"),
	('Evento 6', 2, '2022-06-01', '2022-06-05',5,"./loghi/undefinited.png"),
	('Evento 6', 3, '2023-06-01', '2023-06-05',5,"./loghi/undefinited.png"),
	('Evento 7', 1, '2021-07-01', '2021-07-05',5,"./loghi/undefinited.png"),
	('Evento 7', 2, '2022-07-01', '2022-07-05',5,"./loghi/undefinited.png"),
	('Evento 7', 3, '2023-07-01', '2023-07-05',5,"./loghi/undefinited.png"),
	('Evento 8', 1, '2021-08-01', '2021-08-05',5,"./loghi/undefinited.png"),
	('Evento 8', 2, '2022-08-01', '2022-08-05',5,"./loghi/undefinited.png"),
	('Evento 8', 3, '2023-08-01', '2023-08-05',5,"./loghi/undefinited.png");

insert into users ( username, password, email, name, surname) 
values
	( 'Matteo s.p.a.', '$2y$10$4V6ftj1n.voq5brATlR8WO6iiQUX1P3ljSJ1/oFSTQwJND2/TKXZ6', 'terranovamtt@gmail.com', 'Matteo', 'Terranova'),
	( 'Azienda 1 s.p.a.', '$2y$10$vXDw4LTcL9lQyCPmn0OttOYIGzoJ4TTbzH9zACOwz1Lowclom4jxu', 'azienda1@gmail.com', 'Nome 1', 'Cognome 1'),
	( 'Azienda 2 s.p.a.', '$2y$10$.4s6Wx1OySf5aoTV6p9NKuLH2ZPDAhVjJKwfvKiGnHA1WTfSEqS.K', 'azienda2@gmail.com', 'Nome 2', 'Cognome 2'),
	( 'Azienda 3 s.p.a.', '$2y$10$PJ/syfzme78zWufBFgkPae5QxbrssxDkVp94LtyFJopJ3vHK.QzOS', 'azienda3@gmail.com', 'Nome 3', 'Cognome 3');

    
  