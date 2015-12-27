drop table if exists usuarios cascade;

create table usuarios (
  id_usuario    bigserial   constraint pk_usuarios primary key,
  nombre        varchar(15) not null constraint uq_usuarios_nick unique,
  password      char(32)    not null constraint ck_password_lleno
                            check (length(password) = 32)
);

insert into usuarios (nombre, password)
values ('pepe', md5('pepe')),
       ('juan', md5('juan'));

drop table if exists citas cascade;

create table citas (
  id_cita       bigserial  constraint pk_citas primary key,
  id_usuario    bigint     not null constraint fk_citas_usuarios
                           references usuarios (id_usuario) 
                           on delete no action on update cascade,
  fecha         date       not null,
  hora          time       not null constraint ck_hora_valida
                           check (hora between '10:00:00' and '20:45:00'),
  constraint uq_citas_unicas unique (fecha, hora)
);