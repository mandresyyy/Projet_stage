create database f_infrastructure;

use f_infrastructure;

create table operateur(
    id int PRIMARY KEY AUTO_INCREMENT,
    operateur varchar(255) not null unique,
    couleur varchar(7) not null default '#212729',
    logo varchar(255) not null default 'img/logo/default.png'
);

create table type_site(
    id int PRIMARY KEY AUTO_INCREMENT,
    type varchar(255) not null unique
);

create table source_energie(
    id int PRIMARY KEY AUTO_INCREMENT,
    source varchar(255) not null unique
);

create table proprietaire_site(
    id int PRIMARY KEY AUTO_INCREMENT,
    proprietaire varchar(255) not null unique
);


create table commune(
    id int PRIMARY KEY AUTO_INCREMENT,
    commune varchar(255) not null ,
    code_c varchar(6) not null,
    district varchar(255) not null,
    code_d varchar(4) not null,
    region varchar(255) not null,
    code_r varchar(2) not null
);

create table technologie(
    id int PRIMARY KEY AUTO_INCREMENT,
    generation varchar(255) not null unique
);

create table type_action(
    id int PRIMARY KEY AUTO_INCREMENT,
    action varchar(255) not null unique
);

create table utilisateur(
    id int PRIMARY KEY AUTO_INCREMENT,
    email varchar(255) not null unique,
    matricule varchar(255) not null,
    nom varchar(255) not null,
    prenom varchar(255) not null,
    motdepasse text not null
);

create table recuperation (
    id int PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur int not null references utilisateur(id),
    code varchar(6) not null,
    date_envoie datetime not null default now()
);

create table logs(
    id int PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur int not null references utilisateur(id),
    id_type_action int not null references type_action(id),
    detail text not null,
    date  datetime not null default now()
);

create table infra(
    id int PRIMARY KEY AUTO_INCREMENT,
    id_operateur int not null references utilisateur(id),
    nom_site varchar(255) not null,
    code_site varchar(255) not null,
    technologie_generation varchar(255) not null,
    id_type_site int not null references type_site(id) ,
    id_proprietaire int not null references proprietaire_site(id),
    mutualise varchar(3) not null,
    id_colloc int not null references utilisateur(id),
    latitude decimal(7,5) not null,
    longitude decimal(7,5) not null,
    hauteur decimal(4,2),
    largeur_canaux decimal(10,2),
    id_commune int not null references commune(id),
    annee_mise_service varchar(10),    
    date_upload date default CURDATE()
);

create table infra_source(
    id_infra int not null references infra(id),
    id_source int not null references source_energie(id)
);

create table infra_technologie(
    id_infra  int not null references infra(id),
    id_technologie int not null references technologie(id)
);

insert into operateur (operateur) values ('Non defini');
insert into operateur (operateur,couleur,logo) values ('Orange','#FFA500','img/logo/orange.jfif'),
('Telma','#FFFF00','img/logo/telma.jfif'),
('Airtel','#EC1C24','img/logo/airtel.jfif'),
('Gulfsat','#ADD8E6','img/logo/Gulfsat.jfif');


insert into technologie (generation) values ('2G'),('3G'),('4G');

insert into type_site (type) values ('Non defini'),('Greenfield'),('Rooftop');

insert into source_energie (source) values ('Non defini'),('Jirama'),('Eolienne'),('Groupe electrogene'),('Solaire'),('Mixte');

insert into proprietaire_site (proprietaire) values ('Non defini'),('ORTM'),('TOM'),('HELIOS'),('OMA'),('SHARING');

insert into utilisateur(email,matricule,nom,prenom,motdepasse) values ('admin@gmail.com','ADM','Admin','admin','1234');

create table type_util(
    id int not null auto_increment primary key,
    type_util varchar(255) not null unique
);

insert into type_util (type_util) values ('Admin'),('Viewer');

alter table utilisateur add column id_type_util int references type_util(id) ;
update utilisateur set id_type_util=1;

create or replace view v_Liste_Region as 
select region,code_r from commune group by region,code_r;

create table etat_compte(
    id int not null auto_increment primary key,
    etat varchar(255) not null unique
);

insert into etat_compte (etat) values ('Valide'),('Suspendu');

alter table utilisateur add column id_etat_compte int default 1 references etat_compte(id);


alter table infra add column en_service boolean default true; 

-- recherche avance infra
create or replace view v_all_infra_info as 
select infra.id,infra.nom_site,infra.en_service,operateur.operateur,type_site.type,proprietaire_site.proprietaire,
commune.commune,commune.region,CASE
        WHEN infra.en_service = true THEN 'en service'
        ELSE 'hors service'
    END AS etat
 from infra join operateur on infra.id_operateur=operateur.id
 join type_site on infra.id_type_site=type_site.id join proprietaire_site on infra.id_proprietaire =proprietaire_site.id 
 join commune on infra.id_commune=commune.id;

select id from v_all_infra_info where concat(nom_site,' ',operateur,' ',type,' ',proprietaire,' ',commune,' ',region) 
like '%bip%' ;


-- multicritere
SELECT i.id
FROM infra_technologie i_t
JOIN infra i ON i_t.id_infra = i.id join infra_source i_src on i.id=i_src.id_infra join commune com on i.id_commune= com.id
WHERE (i.id_operateur =5) 
AND (i_t.id_technologie = 1) 

 group by i.id;


-- view 
create or replace view filtre_infra as 
SELECT i.*,i_t.id_technologie,i_src.id_source,com.code_r
FROM infra_technologie i_t
JOIN infra i ON i_t.id_infra = i.id join infra_source i_src on i.id=i_src.id_infra join commune com on i.id_commune= com.id

-- view all info utilisateur recherche avance 
create or replace view v_all_info_utilisateur as
select utilisateur.* ,etat_compte.etat from utilisateur join etat_compte on utilisateur.id_etat_compte=etat_compte.id;

insert into type_action (action) values ('Connexion'),('Recherche'),('statistique'),('modification'),('insertion'),('liste');