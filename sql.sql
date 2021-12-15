create table user
(
    id       int primary key auto_increment not null,
    id_role  int                            not null,
    name     varchar(50)                    not null,
    lastname varchar(50)                    not null,
    phone    varchar(9)                     not null,
    user     varchar(50)                    not null,
    password varchar(50)                    not null,
    active   tinyint(1) default 1           not null
);

alter table user
add unique usersU (user, phone);

create table role
(
    id   int primary key auto_increment not null,
    name varchar(50)                    not null
);

insert into role (name)
values ('admin'),
    ('user');

create table estado_incidencia
(
    id   int primary key auto_increment not null,
    name varchar(50)                    not null
);

insert into estado_incidencia (id, name)
values (1, 'espera'),
    (2, 'solucionado');

create table incidencia
(
    id              int primary key auto_increment not null,
    id_user         int                            not null,
    id_estado_incid int default 1                  not null,
    descripcion     text                           not null,
    inst_educ       varchar(350)                   not null,
    cod_modul       varchar(350)                   not null,
    cod_local       varchar(350)                   not null,
    distrito        varchar(350)                   not null,
    provincia       varchar(350)                   not null,
    region          varchar(350)                   not null,
    dir_nomb_apell  varchar(350)                   not null,
    dir_telefono    varchar(9)                     not null,
    dir_dni         varchar(8)                     not null,
    dir_correo      varchar(350)                   not null,
    cist_nomb_apell varchar(350)                   not null,
    cist_telefono   varchar(9)                     not null,
    cist_dni        varchar(8)                     not null,
    cist_correo     varchar(350)                   not null
);

create table tipo_incid
(
    id   int primary key auto_increment not null,
    name varchar(50)                    not null
);

insert into tipo_incid (name)
values ('Perdida'),
    ('Robo'),
    ('Vida util');

create table ficha_incidencia
(
    id_incid      int          not null,
    id_tipo_incid int          not null,
    marca         varchar(350) not null,
    modelo        varchar(350) not null,
    serie         varchar(350) not null,
    estado        varchar(350) not null,
    ubicacion     varchar(350) not null,
    observaciones varchar(350) not null
);

alter table user
add constraint idRole_role foreign key (id_role) references role (id) on update restrict on delete restrict;

alter table incidencia
add constraint idUser_user foreign key (id_user) references user (id) on update restrict on delete restrict,
    add constraint idEstadIncid_estadIncid foreign key (id_estado_incid) references estado_incidencia (id) on update restrict on delete restrict;

alter table ficha_incidencia
add constraint idIncid_incidencia foreign key (id_incid) references incidencia (id) on update restrict on delete restrict,
    add constraint idTipoIncid_tipoIncidencia foreign key (id_tipo_incid) references tipo_incid (id) on update restrict on delete restrict;


delimiter $
create procedure createUser(_idRole int, _name varchar(50), _lastName varchar(50), _phone varchar(9), _user varchar(50),
    _password varchar(50))
begin
insert into user (id_role, name, lastName, phone, user, password)
values (_idRole, _name, _lastName, _phone, _user, _password);
end;
$
delimiter ;

delimiter $
create procedure updaUser(_idUser int, _idRole int, _name varchar(50), _lastName varchar(50), _phone varchar(9),
    _user varchar(50), _password varchar(50))
begin
update user
set id_role  = _idRole,
    name     = _name,
    lastName = _lastName,
    phone    = _phone,
    user     = _user,
    password = _password
where id like _idUser;
end;
$
delimiter ;

delimiter $
create procedure getUsers(_idRole varchar(5))
begin
select u.id       as id,
    r.name     as role,
    u.name     as name,
    u.lastName as lastName,
    u.phone    as phone,
    u.user     as user,
           u.active   as active
from user u
    inner join role r on u.id_role = r.id
where u.id_role like _idRole;
end;
$
delimiter ;

delimiter $
create procedure login(_user varchar(50), _password varchar(50))
begin
select u.id       as id,
    r.name     as role,
    u.name     as name,
    u.lastName as lastName,
    u.phone    as phone,
    u.user     as user,
           u.active   as active
from user u
    inner join role r on u.id_role = r.id
where user like _user
        and password like _password
        and active like 1;
end;
$
delimiter ;

delimiter $
create procedure updaPassw(_id int, _user varchar(50), _oldPassword varchar(50), _newPassword varchar(50))
begin
update user set password = _newPassword where id like _id and user like _user and password like _oldPassword;
end;
$
delimiter ;

delimiter $
create procedure updaRole(_id int, _user varchar(50), _idRole varchar(50))
begin
update user set id_role = _idRole where id like _id and user like _user;
end;
$
delimiter ;

delimiter $
create procedure getRoles()
begin
select * from role;
end;
$
delimiter ;

delimiter $
create procedure getTipoIncid()
begin
select * from tipo_incid;
end;
$
delimiter ;

delimiter $
create procedure getEstadoIncid()
begin
select * from estado_incidencia;
end;
$
delimiter ;

delimiter $
create procedure getIncidencias(_idStateInci varchar(5))
begin
select i.id                            as idInci,
    i.descripcion                   as descripcion,
    i.inst_educ                     as inst_educ,
    i.cod_modul                     as cod_modul,
    i.cod_local                     as cod_local,
    i.distrito                      as distrito,
    i.provincia                     as provincia,
    i.region                        as region,
    i.dir_nomb_apell                as dir_nomb_apell,
    i.dir_telefono                  as dir_telefono,
    i.dir_dni                       as dir_dni,
    i.dir_correo                    as dir_correo,
    i.cist_nomb_apell               as cist_nomb_apell,
    i.cist_telefono                 as cist_telefono,
    i.cist_dni                      as cist_dni,
    i.cist_correo                   as cist_correo,
    u.id                            as user_id,
    concat(u.name, ' ', u.lastname) as usuario,
    u.phone                         as user_phone,
    ei.id                           as id_esta_inci,
    ei.name                         as name_esta_inci
from incidencia i
         inner join user u on i.id_user = u.id
         inner join estado_incidencia ei on i.id_estado_incid = ei.id
where i.id_estado_incid like _idStateInci;
end;
$ delimiter ;

delimiter $
create procedure createIncid(_id_user int, _id_estado_incid int, _descripcion text, _inst_educ varchar(350),
    _cod_modul varchar(350), _cod_local varchar(350), _distrito varchar(350),
    _provincia varchar(350), _region varchar(350), _dir_nomb_apell varchar(350),
    _dir_telefono varchar(9), _dir_dni varchar(8), _dir_correo varchar(350),
    _cist_nomb_apell varchar(350), _cist_telefono varchar(9), _cist_dni varchar(8),
    _cist_correo varchar(350))
begin
    insert incidencia (id_user, id_estado_incid, descripcion, inst_educ, cod_modul, cod_local, distrito, provincia,
                       region, dir_nomb_apell, dir_telefono, dir_dni, dir_correo, cist_nomb_apell, cist_telefono,
                       cist_dni, cist_correo)
        value (_id_user, _id_estado_incid, _descripcion, _inst_educ, _cod_modul, _cod_local, _distrito, _provincia,
               _region, _dir_nomb_apell, _dir_telefono, _dir_dni, _dir_correo, _cist_nomb_apell, _cist_telefono,
               _cist_dni, _cist_correo);
end;
$
delimiter ;

delimiter $
create procedure getIncidencia(_idIncid int)
begin
select i.id                            as idInci,
    i.descripcion                   as descripcion,
    i.inst_educ                     as inst_educ,
    i.cod_modul                     as cod_modul,
    i.cod_local                     as cod_local,
    i.distrito                      as distrito,
    i.provincia                     as provincia,
    i.region                        as region,
    i.dir_nomb_apell                as dir_nomb_apell,
    i.dir_telefono                  as dir_telefono,
    i.dir_dni                       as dir_dni,
    i.dir_correo                    as dir_correo,
    i.cist_nomb_apell               as cist_nomb_apell,
    i.cist_telefono                 as cist_telefono,
    i.cist_dni                      as cist_dni,
    i.cist_correo                   as cist_correo,
    u.id                            as user_id,
    concat(u.name, ' ', u.lastname) as usuario,
    u.phone                         as user_phone,
    ei.id                           as id_esta_inci,
    ei.name                         as name_esta_inci
from incidencia i
         inner join user u on i.id_user = u.id
         inner join estado_incidencia ei on i.id_estado_incid = ei.id
where i.id like _idIncid;
end;
$ delimiter ;

delimiter $
create procedure deleteIncidencia(_idIncid int)
begin
delete from ficha_incidencia where id_incid like _idIncid;
delete from incidencia where id like _idIncid;
end;
$ delimiter ;

delimiter $
create procedure getFichas(_idIncid int)
begin
select *
from ficha_incidencia fi
         inner join tipo_incid t on fi.id_tipo_incid = t.id
where fi.id_incid like _idIncid;
end;
$ delimiter ;

delimiter $
create procedure createFicha(_id_incid int, _id_tipo_incid int, _marca varchar(350), _modelo varchar(350),
    _serie varchar(350), _estado varchar(350), _ubicacion varchar(350),
    _observaciones varchar(350))
begin
insert into ficha_incidencia (id_incid, id_tipo_incid, marca, modelo, serie, estado, ubicacion, observaciones)
    value (_id_incid, _id_tipo_incid, _marca, _modelo, _serie, _estado, _ubicacion, _observaciones);
end;
$ delimiter ;


insert into user (id, id_role, name, lastname, phone, user, password, active)
    value (1, 1, 'Admin', 'Admin', '961266733', 'admin@gmail.com', '3UJBeGmgxKCkHpQdQB4ZtgwkxIHJnU+e7br24M2XCUE=', 1);

insert into incidencia (id, id_user, id_estado_incid, descripcion, inst_educ, cod_modul, cod_local, distrito, provincia,
                        region, dir_nomb_apell, dir_telefono, dir_dni, dir_correo, cist_nomb_apell, cist_telefono,
                        cist_dni, cist_correo)
    value (1, 1, 1, 'descripcion', 'inst_educ', 'cod_modul', 'cod_local', 'distrito', 'provincia',
           'region', 'dir_nomb_apell', '123456789', '12345678', 'dir_correo', 'cist_nomb_apell', '123456789',
           '12345678', 'cist_correo');

insert into ficha_incidencia (id_incid, id_tipo_incid, marca, modelo, serie, estado, ubicacion, observaciones)
    value (1, 1, 'marca', 'modelo', 'serie', 'estado', 'ubicacion', 'observaciones');



