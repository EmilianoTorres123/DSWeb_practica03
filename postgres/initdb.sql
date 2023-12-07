CREATE USER myuser;
CREATE DATABASE ejemplo;
GRANT ALL PRIVILEGES ON DATABASE ejemplo TO myuser;
ALTER DATABASE ejemplo OWNER TO myuser;

\connect ejemplo
create table empleado
(
    clave integer NOT NULL,
    nombre character varying,
    direccion character varying,
    telefeno character varying
);
ALTER TABLE empleado OWNER TO myuser;
