-- create database f_infrastructure;

create database B_infra_final;

-- use f_infrastructure;
use B_infra_final;

-- create database infra_reel;
-- use infra_reel;
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
    coloc text ,
    latitude decimal(7,5),
    longitude decimal(7,5),
    hauteur decimal(7,2),
    largeur_canaux decimal(10,2),
    id_commune int not null references commune(id),
    annee_mise_service varchar(10),    
    date_upload date default CURDATE()
);


create table infra_source(
    id_infra int not null ,
    id_source int not null references source_energie(id) ,
    foreign key (id_infra) references infra (id) on delete CASCADE
);

create table infra_technologie(
    id_infra  int not null ,
    id_technologie int not null references technologie(id) ,
    foreign key (id_infra) references infra (id) on delete CASCADE
);

insert into operateur (operateur) values ('Non defini');
insert into operateur (operateur,couleur) values ('Orange','#FFA500'),
('Telma','#FFFF00'),
('Airtel','#EC1C24'),
('Gulfsat','#ADD8E6');


insert into technologie (generation) values ('Non defini'),('2G'),('3G'),('4G');

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
-- create or replace view v_all_infra_info as 
-- select infra.id,infra.nom_site,infra.en_service,operateur.operateur,type_site.type,proprietaire_site.proprietaire,
-- commune.commune,commune.region,CASE
--         WHEN infra.en_service = true THEN 'en service'
--         ELSE 'hors service'
--     END AS etat
--  from infra join operateur on infra.id_operateur=operateur.id
--  join type_site on infra.id_type_site=type_site.id join proprietaire_site on infra.id_proprietaire =proprietaire_site.id 
--  join commune on infra.id_commune=commune.id;

-- select id from v_all_infra_info where concat(nom_site,' ',operateur,' ',type,' ',proprietaire,' ',commune,' ',region) 
-- like '%bip%' ;


-- multicritere
-- SELECT i.id
-- FROM infra_technologie i_t
-- JOIN infra i ON i_t.id_infra = i.id join infra_source i_src on i.id=i_src.id_infra join commune com on i.id_commune= com.id
-- WHERE (i.id_operateur =5) 
-- AND (i_t.id_technologie = 1) 

--  group by i.id;


-- view 

create or replace view filtre_infra as 
SELECT i.id,i.id_operateur,i.id_type_site,i.en_service,i.mutualise,i_t.id_technologie,i_src.id_source,com.code_r
FROM infra_technologie i_t
JOIN infra i ON i_t.id_infra = i.id join infra_source i_src on i.id=i_src.id_infra join commune com on i.id_commune= com.id 
where latitude is not null and longitude is not  null;

-- view all info utilisateur recherche avance 
-- create or replace view v_all_info_utilisateur as
-- select utilisateur.* ,etat_compte.etat from utilisateur join etat_compte on utilisateur.id_etat_compte=etat_compte.id;

insert into type_action (action) values ('Connexion'),('Recherche'),('statistique'),('modification'),('insertion'),('liste');


-- dashboard

-- geographie
create or replace view Liste_Region as select region,code_r from commune group by region,code_r; 
create or replace view Liste_District as select district,code_d,code_r,region from commune group by district,code_d,region,code_r; 

--  region_district_operateur 
create or replace view Liste_Operateur as select id,operateur from operateur where operateur!='Non defini';
-- on infra.id_operateur=Liste_Operateur.id 
-- create or replace view v_Operateur_geographique as select * from Liste_District full join Liste_Operateur ;
-- create or replace view v_Operateur_geographique as select * from Liste_District full join Liste_Operateur ;

-- avec mutualise
create or replace view Liste_mutualise as select distinct(mutualise) as mutualise from infra;

-- avec proprio
create or replace view Liste_proprietaire as select id as id_proprietaire,proprietaire from proprietaire_site; 

-- create or replace view v_Operateur_geographique_proprio as  select * from Liste_proprietaire full join v_Operateur_geographique;

-- region district operateur type
create or replace view Liste_Type as select id as id_type,type from type_site ;

-- create or replace view v_Operateur_geographique_Type as
--  select *from 
-- Liste_Type full join v_Operateur_geographique_proprio;



-- region district operateur type techno
create or replace view  Liste_Techno as select generation,id as id_tech from technologie;


-- create or replace view v_Operateur_geographique_Region_Type_Techno as select * from Liste_Techno full join 
-- v_Operateur_geographique_Type;

-- region district operateur type  source
create or replace view Liste_Source as select source,id as id_source from source_energie;

-- create or replace view v_Operateur_geographique_Region_Type_Source as 
-- select * from Liste_Source full join v_Operateur_geographique_Type;

-- infra_commune

create or replace view v_Infra_Commune as select infra.id as id_infra,infra.id_operateur,infra.id_type_site,commune.*,1 as nb, 
infra.mutualise,id_proprietaire from infra join commune on infra.id_commune=commune.id ;

-- infra commune operateur
create or replace view v_Infra_Commune_Operateur as select v_Infra_Commune.*,operateur.operateur from v_Infra_Commune join 
operateur on v_Infra_Commune.id_operateur=operateur.id;

-- infra commune operateur type proprio
create or replace view v_Infra_Commune_Operateur_Type as select v_Infra_Commune_Operateur.*,type_site.type,proprietaire_site.proprietaire from 
v_Infra_Commune_Operateur join type_site on v_Infra_Commune_Operateur.id_type_site=type_site.id join proprietaire_site on 
proprietaire_site.id=v_Infra_Commune_Operateur.id_proprietaire;

create or replace view v_Infra_Commune_Operateur_Type_CD as select operateur,type,commune,district,region,proprietaire,mutualise,nb 
from v_Infra_Commune_Operateur_Type;
-- infra par region et dist misy 0 izay tsy misy

-- create or replace view v_Infra_Geographique as SELECT v_Operateur_geographique_Type.operateur,v_Operateur_geographique_Type.region,
-- v_Operateur_geographique_Type.district,v_Operateur_geographique_Type.type,v_Infra_Commune_Operateur_Type.mutualise as mut,COALESCE(v_Infra_Commune_Operateur_Type.nb, 0) AS nb,
-- v_Operateur_geographique_Type.proprietaire
-- FROM v_Operateur_geographique_Type
-- LEFT JOIN v_Infra_Commune_Operateur_Type ON v_Operateur_geographique_Type.code_d = v_Infra_Commune_Operateur_Type.code_d and 
-- v_Infra_Commune_Operateur_Type.id_operateur=v_Operateur_geographique_Type.id and 
-- v_Infra_Commune_Operateur_Type.id_type_site=v_Operateur_geographique_Type.id_type and 
-- v_Infra_Commune_Operateur_Type.id_proprietaire=v_Operateur_geographique_Type.id_proprietaire ;

-- create or replace view v_Infra_Geographique_F as
-- select v_Infra_Geographique.region,v_Infra_Geographique.district,v_Infra_Geographique.operateur,v_Infra_Geographique.type,v_Infra_Geographique.nb,
-- COALESCE(mut,'NON') as mutualise,v_Infra_Geographique.proprietaire from v_Infra_Geographique ;


create or replace view v_Infra_Technologie as select v_Infra_Commune_Operateur_Type.* ,technologie.generation,
technologie.id as id_technologie from
infra_technologie join technologie on infra_technologie.id_technologie=technologie.id join v_Infra_Commune_Operateur_Type
 on v_Infra_Commune_Operateur_Type.id_infra=infra_technologie.id_infra;

 create or replace view v_Infra_Technologie_CD as select operateur,type,commune,district,region,proprietaire,mutualise,nb,generation
 from v_Infra_Technologie;

-- stats par tech
-- create or replace view v_Technologie_Infra as SELECT v_Operateur_geographique_Region_Type_Techno.generation,
-- v_Operateur_geographique_Region_Type_Techno.type,v_Operateur_geographique_Region_Type_Techno.district,v_Operateur_geographique_Region_Type_Techno.region,
-- v_Operateur_geographique_Region_Type_Techno.operateur,
-- COALESCE(v_Infra_Technologie.nb,0) as nb
-- from v_Operateur_geographique_Region_Type_Techno left join v_Infra_Technologie
-- on v_Operateur_geographique_Region_Type_Techno.code_d = v_Infra_Technologie.code_d and 
-- v_Infra_Technologie.id_operateur=v_Operateur_geographique_Region_Type_Techno.id and 
-- v_Infra_Technologie.id_type_site=v_Operateur_geographique_Region_Type_Techno.id_type and
-- v_Infra_Technologie.id_technologie=v_Operateur_geographique_Region_Type_Techno.id_tech and
-- v_Infra_Technologie.id_proprietaire=v_Operateur_geographique_Region_Type_Techno.id_proprietaire ;


-- stats par source  
create or replace view v_Infra_Source as select v_Infra_Commune_Operateur_Type.*,source_energie.source,source_energie.id as id_source
from infra_source join v_Infra_Commune_Operateur_Type on infra_source.id_infra=v_Infra_Commune_Operateur_Type.id_infra 
join source_energie on source_energie.id=infra_source.id_source;

create or replace view v_Infra_Source_CD as select operateur,type,commune,district,region,proprietaire,mutualise,nb,source from v_Infra_Source;

-- create or replace view v_Source_Infra as SELECT v_Operateur_geographique_Region_Type_Source.source,
-- v_Operateur_geographique_Region_Type_Source.type,v_Operateur_geographique_Region_Type_Source.district,
-- v_Operateur_geographique_Region_Type_Source.region,v_Operateur_geographique_Region_Type_Source.operateur,
-- COALESCE(v_Infra_Source.nb,0) as nb
-- from v_Operateur_geographique_Region_Type_Source left join v_Infra_Source
-- on v_Operateur_geographique_Region_Type_Source.code_d = v_Infra_Source.code_d and 
-- v_Infra_Source.id_operateur=v_Operateur_geographique_Region_Type_Source.id and 
-- v_Infra_Source.id_type_site=v_Operateur_geographique_Region_Type_Source.id_type and 
-- v_Infra_Source.id_source=v_Operateur_geographique_Region_Type_Source.id_source and 
-- v_Infra_Source.id_proprietaire=v_Operateur_geographique_Region_Type_Source.id_proprietaire;

-- Chart Infra par operateur
create or replace view v_Op_Infra as select Liste_Operateur.id,count(*) as nb from infra join Liste_Operateur on infra.id_operateur=Liste_Operateur.id group by infra.id_operateur; 

create or replace view v_Stats_InfraByOperateur as select Liste_Operateur.couleur,Liste_Operateur.operateur,COALESCE(v_Op_Infra.nb,0) as nb from Liste_Operateur left join v_Op_Infra on 
Liste_Operateur.id=v_Op_Infra.id;

-- Chart technologie par operateur
create or replace view v_Tech_Op_Infra as
select Liste_Operateur.id,infra_technologie.id_technologie,count(*) as nb from infra_technologie join infra on
infra.id=infra_technologie.id_infra join Liste_Operateur 
on Liste_Operateur.id=infra.id_operateur
group by infra.id_operateur,infra_technologie.id_technologie;

create or replace view All_tech_operateur as select * from Liste_Operateur full join Liste_Techno;

create or replace view v_Stats_TechByOperateur as select All_tech_operateur.*,COALESCE(v_Tech_Op_Infra.nb,0)as nb
 from All_tech_operateur left join v_Tech_Op_Infra on All_tech_operateur.id_tech=v_Tech_Op_Infra.id_technologie and 
 All_tech_operateur.id=v_Tech_Op_Infra.id;

-- Chart Infra par source

create or replace view v_Source_infra_count as select infra_source.id_source,count(*) as nb from 
 infra_source group by infra_source.id_source;

create or replace view v_Stats_SourceByInfra as select Liste_source.source,COALESCE(v_Source_infra_count.nb,0) as nb from
Liste_source left join v_Source_infra_count on Liste_source.id_source=v_Source_infra_count.id_source;

-- Chart infra par mutualise
create or replace view v_Stats_mutualise as select mutualise,count(*) as nb from infra group by mutualise;

-- Chart proprietaire 
 create or replace view v_Stats_proprio as select proprietaire_site.proprietaire,count(*) as nb from infra join 
 proprietaire_site on infra.id_proprietaire=proprietaire_site.id group by infra.id_proprietaire,proprietaire_site.proprietaire;


-- Chart type
create or replace view v_infra_type as select id_type_site,count(*) as nb from infra group by id_type_site;
create or replace view v_Stats_type as select type,COALESCE(nb,0) as nb from Liste_Type left join v_infra_type on 
v_infra_type.id_type_site=Liste_Type.id_type;

-- alimentation card
create or replace view nb_infra as select count(*) as nb from infra;

create or replace view nb_operateur as select count(*) as nb from operateur where operateur!='Non defini';

create or replace view nb_technologie as select count(*) as nb from technologie where generation!='Non defini';


-- Releve

create table releve(
    id int auto_increment primary key not null ,
    id_upload varchar(25) not null,
    date_upload date default CURDATE(),
    description text not null,
    date_releve timestamp not null,
    longitude decimal(7,5) not null,
    latitude  decimal(7,5) not null,
    id_operateur int not null references operateur(id),
    operateur_capter varchar(50) not null,
    tech varchar(50) not null ,
    level DECIMAL(3,0) not null,
    speed int not null,
    altitude decimal(2,0) not null,
    couleur varchar(25) not null
);

create table mise_a_jour(
    domaine varchar(20) not null,
    etat boolean not null
);
insert into mise_a_jour values('infra',true),('releve',true);
insert into mise_a_jour values('technologie',true),('type',true),('source',true),('proprietaire',true);
insert into mise_a_jour values('operateur',true);
insert into mise_a_jour values('infra_releve',true);
insert into mise_a_jour values('operateur_releve',true);

insert into mise_a_jour values('technologie_releve',true);
insert into mise_a_jour values('infra_source',true);
insert into mise_a_jour values('infra_tech',true);

insert into mise_a_jour values('op_source',true);
insert into mise_a_jour values('op_tech',true);
insert into mise_a_jour values('type_source',true);
insert into mise_a_jour values('type_tech',true);
-- USE information_schema;

-- SELECT 
--     table_name
-- FROM
--     referential_constraints
-- WHERE
--     constraint_schema = 'B_infra_final'
--         AND referenced_table_name = 'infra'
--         AND delete_rule = 'CASCADE';

alter table recuperation add column etat int default 0;

-- liste releve 
-- create or replace view v_liste_releve as select id_upload,date_upload,id_operateur,description,date_releve from releve group by id_upload;

