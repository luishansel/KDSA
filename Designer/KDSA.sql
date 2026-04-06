/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     02/04/2026 10:12:50 a. m.                    */
/*==============================================================*/


/*==============================================================*/
/* Table: KDSA000A                                              */
/*==============================================================*/
create table KDSA000A
(
   USUARIO_000          varchar(15)  comment '',
   FECHA_000            timestamp  comment '',
   TABLA_000            char(30)  comment '',
   LLAVE1_000           char(15)  comment '',
   LLAVE2_000           char(15)  comment '',
   OPERACION_000        varchar(20)  comment ''
);

/*==============================================================*/
/* Table: KDSA001A                                              */
/*==============================================================*/
create table KDSA001A
(
   FECHA_001            date not null  comment '',
   DESC_001             varchar(100)  comment '',
   DIA_001              varchar(15)  comment '',
   primary key (FECHA_001)
);

/*==============================================================*/
/* Table: KDSA002A                                              */
/*==============================================================*/
create table KDSA002A
(
   USUARIO_REL          varchar(15) not null  comment '',
   NOMBRE_002           varchar(100)  comment '',
   CLAVE_002            varchar(100)  comment '',
   CORREO_002           varchar(100)  comment '',
   SUPERVISOR_002       bool  comment '',
   ACADEMICO_002        bool  comment 'Establece el permiso para el supervisor academico',
   ACTIVO_002           bool  comment '',
   primary key (USUARIO_REL)
);

/*==============================================================*/
/* Table: KDSA003A                                              */
/*==============================================================*/
create table KDSA003A
(
   GRUPO_REL            varchar(15) not null  comment '',
   NOMBRE_003           varchar(50)  comment '',
   primary key (GRUPO_REL)
);

/*==============================================================*/
/* Table: KDSA004A                                              */
/*==============================================================*/
create table KDSA004A
(
   PAGINA_REL           varchar(20) not null  comment '',
   DESC_004             char(50)  comment '',
   primary key (PAGINA_REL)
);

/*==============================================================*/
/* Table: KDSA005A                                              */
/*==============================================================*/
create table KDSA005A
(
   PAGINA_REL           varchar(20) not null  comment '',
   GRUPO_REL            varchar(15) not null  comment '',
   INCLUIR_005          bool  comment '',
   MODIFICAR_005        bool  comment '',
   BORRAR_005           bool  comment '',
   ANULAR_005           bool  comment '',
   primary key (PAGINA_REL, GRUPO_REL)
);

/*==============================================================*/
/* Table: KDSA006A                                              */
/*==============================================================*/
create table KDSA006A
(
   GRUPO_REL            varchar(15) not null  comment '',
   USUARIO_REL          varchar(15) not null  comment '',
   primary key (GRUPO_REL, USUARIO_REL)
);

/*==============================================================*/
/* Table: KDSA007A                                              */
/*==============================================================*/
create table KDSA007A
(
   ENLACE_REL           varchar(30) not null  comment 'Contiene el Código del Curso, la Fecha y la Hora de generación',
   CURSO_REL            varchar(10)  comment '',
   FECHA_007            datetime  comment '',
   DESTINATARIO_007     varchar(100)  comment '',
   ESTADO_007           int  comment '0.-Activo
             1.-Registrado
             2.-Matriculado
             3.-Completado',
   primary key (ENLACE_REL)
);

alter table KDSA007A comment 'Guarda el argumento de los enlaces enviados para verificar s';

/*==============================================================*/
/* Table: KDSA008A                                              */
/*==============================================================*/
create table KDSA008A
(
   FIRMA_REL            varchar(10) not null  comment '',
   NOMBRE_008           varchar(100)  comment '',
   CARGO_008            varchar(100)  comment '',
   SEXO_008             char(1)  comment '',
   primary key (FIRMA_REL)
);

alter table KDSA008A comment 'Firmas para la Constancia de Estiduiante activo';

/*==============================================================*/
/* Table: KDSA010A                                              */
/*==============================================================*/
create table KDSA010A
(
   ESTUDIANTE_REL       varchar(10) not null  comment '',
   PROSPECTO_REL        varchar(10)  comment '',
   NOMBRES_010          varchar(50)  comment '',
   APELLIDOS_010        varchar(50)  comment '',
   SEXO_010             char(1)  comment '',
   CEDULA_010           varchar(20)  comment '',
   FECHANAC_010         date  comment '',
   DOMICILIO_010        varchar(50)  comment '',
   DIRECCION_010        varchar(200)  comment '',
   TELEFONO_010         varchar(20)  comment '',
   CELULAR_010          varchar(20)  comment '',
   CORREO_010           varchar(100)  comment '',
   EMERGENCIA_010       varchar(100)  comment '',
   PARENTESCO_010       varchar(50)  comment '',
   NIVELACADEMICO_010   varchar(50)  comment '',
   POSTGRADO_010        bool  comment '',
   MAESTRIA_010         bool  comment '',
   LUGARTRABAJO_010     varchar(100)  comment '',
   PUESTO_010           varchar(50)  comment '',
   TELEFONOEMPRESA_010  varchar(10)  comment '',
   primary key (ESTUDIANTE_REL)
);

/*==============================================================*/
/* Table: KDSA011A                                              */
/*==============================================================*/
create table KDSA011A
(
   ESTUDIANTE_REL       varchar(10) not null  comment '',
   ARCHIVO_REL          varchar(50) not null  comment '',
   DESC_011             varchar(150)  comment '',
   RUTA_011             varchar(250)  comment '',
   primary key (ESTUDIANTE_REL, ARCHIVO_REL)
);

/*==============================================================*/
/* Table: KDSA020A                                              */
/*==============================================================*/
create table KDSA020A
(
   CURSO_REL            varchar(10) not null  comment '',
   CURSOINATEC_REL      varchar(10)  comment '',
   NOMBRE_020           varchar(100)  comment '',
   FECHAINI_020         date  comment '',
   FECHAFIN_020         date  comment '',
   HORAINI_020          time  comment '',
   HORAFIN_020          time  comment '',
   LUNES_020            bool  comment '',
   MARTES_020           bool  comment '',
   MIERCOLES_020        bool  comment '',
   JUEVES_020           bool  comment '',
   VIERNES_020          bool  comment '',
   SABADO_020           bool  comment '',
   DOMINGO_020          bool  comment '',
   TIPO_020             numeric(2,0)  comment '0.- Seminario
             1.- Curso
             2.- Carrera
             3.- Taller
             4.- Diplomado
             5.- Webinar
             6.- Workshop
             7.- Teambuilding
             8.- Bootcamp
             9.- Programa
             10.- Masterclass',
   TIPOASISTENCIA_020   numeric(1,0)  comment '0.- Presencial
             1.- Virtual',
   TURNO_020            numeric(1,0)  comment '0.- Nocturno
             1.- Sabatino
             2.- Dominical
             3.- Matutino
             4.- Vespertino',
   CONVOCATORIA_020     varchar(10)  comment '',
   GRUPO_020            numeric(1,0)  comment '',
   MONEDA_020           numeric(1,0)  comment '0.- Córdobas
             1.- Dólares',
   VALOR_020            decimal(10,2)  comment '',
   MATRICULA_020        decimal(10,2)  comment '',
   CUOTA_020            decimal(10,2)  comment '',
   CERTIFICACION_020    decimal(10,2)  comment '',
   MORA_020             numeric(2,0)  comment '',
   MAXIMO_020           numeric(2,0)  comment '',
   ACTIVO_020           bool  comment '',
   CERTIFICAR_020       bool  comment 'Permite certificar a los estudiantes del curso aun cuando esta activo. Se usa cuando el curso es empresarial y se requiere certificar inmediatamente despues de terminarlo.',
   CERRADOANTES_020     bool  comment '',
   CERTDIGITAL_020      bool  comment '',
   primary key (CURSO_REL)
);

/*==============================================================*/
/* Table: KDSA021A                                              */
/*==============================================================*/
create table KDSA021A
(
   MODULO_REL           varchar(10) not null  comment '',
   DOCENTE_REL          varchar(10)  comment '',
   CURSO_REL            varchar(10)  comment '',
   NUMERO_021           numeric(2,0)  comment '',
   NOMBRE_021           varchar(200)  comment '',
   FECHAINI_021         date  comment '',
   FECHAFIN_021         date  comment '',
   VALOR_021            decimal(10,2)  comment '',
   primary key (MODULO_REL)
);

/*==============================================================*/
/* Table: KDSA022A                                              */
/*==============================================================*/
create table KDSA022A
(
   CURSO_REL            varchar(10) not null  comment '',
   DETFECHA_REL         numeric(2,0) not null  comment '',
   FECHA_022            date  comment '',
   DIA_022              varchar(15)  comment '',
   MOTIVO_022           varchar(200)  comment '',
   primary key (CURSO_REL, DETFECHA_REL)
);

/*==============================================================*/
/* Table: KDSA023A                                              */
/*==============================================================*/
create table KDSA023A
(
   CURSO_REL            varchar(10) not null  comment '',
   DOCENTE_REL          varchar(10) not null  comment '',
   DETINCIDENCIA_REL    numeric(3,0) not null  comment '',
   FECHA_023            datetime  comment '',
   TEXTO_023            varchar(500)  comment '',
   primary key (CURSO_REL, DOCENTE_REL, DETINCIDENCIA_REL)
);

/*==============================================================*/
/* Table: KDSA024A                                              */
/*==============================================================*/
create table KDSA024A
(
   CURSO_REL            varchar(10) not null  comment '',
   DOCUMENTO_REL        numeric(2,0) not null  comment '',
   ARCHIVO_024          varchar(100)  comment '',
   RUTA_024             varchar(250)  comment '',
   primary key (CURSO_REL, DOCUMENTO_REL)
);

/*==============================================================*/
/* Table: KDSA030A                                              */
/*==============================================================*/
create table KDSA030A
(
   MATRICULA_REL        varchar(10) not null  comment '',
   ESTUDIANTE_REL       varchar(10)  comment '',
   CURSO_REL            varchar(10)  comment '',
   FECHA_030            date  comment '',
   DESCUENTO_030        decimal(5,2)  comment '',
   MOTIVO_030           varchar(150)  comment '',
   MEDIO_030            varchar(200)  comment '',
   FUENTEINGRESO_030    numeric(1,0)  comment '0.- Propios
             1.- Empresa
             2.- Papás
             3.- Familiar',
   PRIMERAVEZ_030       bool  comment '',
   BECADO_030           bool  comment '',
   BECADOPOR_030        varchar(100)  comment '',
   INATEC_030           bool  comment '',
   TIPOASISTENCIA_030   numeric(1,0)  comment '',
   ESTADO_030           numeric(1,0)  comment '0.-Activo
             1.-Inactivo
             2.-Deserción
             3.-Certificado
             4.-Anulado
             5.-Baja (Estudiantes que nunca iniciaron el curso)',
   DOCIDENTIDAD_030     bool  comment '',
   DOCACADEMICO_030     bool  comment '',
   NOTIFICADO_030       bool  comment 'Verificación del envío de correo para activación de la matrícula',
   CERTDIGITAL_030      bool  comment '',
   primary key (MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA031A                                              */
/*==============================================================*/
create table KDSA031A
(
   FECHA_031            datetime not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   ESTADO_031           numeric(1,0)  comment '0.- Notificado
             1.- Activado',
   primary key (FECHA_031)
);

alter table KDSA031A comment 'La tabla se llena cuando el estudiante activa la matrícula m';

/*==============================================================*/
/* Table: KDSA040A                                              */
/*==============================================================*/
create table KDSA040A
(
   PAGO_REL             varchar(10) not null  comment '',
   FECHA_040            date  comment '',
   NOMBRE_040           varchar(100)  comment '',
   RECIBO_040           varchar(10)  comment '',
   SERIE_040            char(1)  comment '',
   MONTO_040            decimal(10,2)  comment '',
   RETENCION_DGI_040    decimal(10,2)  comment 'No se presenta en la pantalla cuando sea un pago ordinario del estudiante',
   RETENCION_ALCALDIA_040 decimal(10,2)  comment '',
   MONEDA_040           numeric(1,0)  comment '0.- Córdobas
             1.- Dólares',
   TIPOCAMBIO_040       decimal(10,4)  comment '',
   CONCEPTO_040         varchar(200)  comment '',
   TIPOPAGO_040         numeric(1,0)  comment '0.- Efectivo
             1.- Tarjeta
             2.- Cheque
             3.- Depósito FICOHSA
             4.- Depósito BAC
             5.- eCommerce',
   NUMEROCK_040         varchar(50)  comment 'Número de Cheque y Banco',
   BANCOCK_040          varchar(50)  comment '',
   OTROINGRESO_040      bool  comment '',
   EMPRESARIAL_040      bool  comment '',
   INATEC_040           bool  comment '',
   ANULADO_040          bool  comment '',
   primary key (PAGO_REL)
);

/*==============================================================*/
/* Table: KDSA041A                                              */
/*==============================================================*/
create table KDSA041A
(
   COBRO_REL            varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   PAGO_REL             varchar(10) not null  comment '',
   MONTO_041            decimal(10,2)  comment '',
   DESCUENTO_041        decimal(10,2)  comment '',
   primary key (COBRO_REL, MATRICULA_REL, PAGO_REL)
);

/*==============================================================*/
/* Table: KDSA042A                                              */
/*==============================================================*/
create table KDSA042A
(
   COBRO_REL            varchar(10) not null  comment '',
   PAGO_REL             varchar(10) not null  comment '',
   MONTO_042            decimal(10,2)  comment '',
   RETENCION_DGI_042    decimal(10,2)  comment '',
   RETENCION_ALCALDIA_042 decimal(10,2)  comment '',
   primary key (COBRO_REL, PAGO_REL)
);

/*==============================================================*/
/* Table: KDSA043A                                              */
/*==============================================================*/
create table KDSA043A
(
   COBRO_REL            varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   COBROINATEC_REL      varchar(10) not null  comment '',
   PAGO_REL             varchar(10) not null  comment '',
   MONTO_043            decimal(10,2)  comment '',
   RETENCION_DGI_043    decimal(10,2)  comment '',
   RETENCION_ALCALDIA_043 decimal(10,2)  comment '',
   primary key (COBRO_REL, MATRICULA_REL, COBROINATEC_REL, PAGO_REL)
);

/*==============================================================*/
/* Table: KDSA044A                                              */
/*==============================================================*/
create table KDSA044A
(
   FECHA_044            date not null  comment '',
   primary key (FECHA_044)
);

alter table KDSA044A comment 'Fechas cerradas en la caja';

/*==============================================================*/
/* Table: KDSA050A                                              */
/*==============================================================*/
create table KDSA050A
(
   COBRO_REL            varchar(10) not null  comment '',
   KDS_COBRO_REL        varchar(10)  comment '',
   CURSO_REL            varchar(10)  comment '',
   FECHAPREVISTA_050    date  comment '',
   CONCEPTO_050         varchar(150)  comment '',
   MONTO_050            decimal(10,2)  comment '',
   MONEDA_050           numeric(1,0)  comment '0.- Córdobas
             1.- Dólares',
   TIPO_050             numeric(1,0)  comment '0.- Cuota
             1.- Moratorio
             2.- Matricula
             3.- Empresarial
             4.- INATEC
             5.- Otros
             6.- Cuota especial',
   ACTIVO_050           bool  comment '',
   ANULADO_050          bool  comment '',
   primary key (COBRO_REL)
);

/*==============================================================*/
/* Table: KDSA051A                                              */
/*==============================================================*/
create table KDSA051A
(
   COBRO_REL            varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   ADEUDADO_051         decimal(10,2)  comment '',
   ABONADO_051          decimal(10,2)  comment '',
   PAGADO_051           bool  comment '',
   EXONERADO_051        bool  comment '',
   ANULADO_051          bool  comment '',
   primary key (COBRO_REL, MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA052A                                              */
/*==============================================================*/
create table KDSA052A
(
   COBRO_REL            varchar(10) not null  comment '',
   DEUDOR_052           varchar(100)  comment '',
   ABONADO_052          decimal(10,2)  comment '',
   PAGADO_052           bool  comment '',
   EXONERADO_052        bool  comment '',
   ANULADO_052          bool  comment '',
   primary key (COBRO_REL)
);

/*==============================================================*/
/* Table: KDSA053A                                              */
/*==============================================================*/
create table KDSA053A
(
   COBRO_REL            varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   primary key (COBRO_REL, MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA054A                                              */
/*==============================================================*/
create table KDSA054A
(
   COBROINATEC_REL      varchar(10) not null  comment '',
   DESC_054             varchar(150)  comment '',
   RETENCION_DGI_054    decimal(10,2)  comment '',
   RETENCION_ALCALDIA_054 decimal(10,2)  comment '',
   ABONADO_054          decimal(10,2)  comment '',
   PAGADO_054           bool  comment '',
   EXONERADO_054        bool  comment '',
   ANULADO_054          bool  comment '',
   primary key (COBROINATEC_REL)
);

/*==============================================================*/
/* Table: KDSA055A                                              */
/*==============================================================*/
create table KDSA055A
(
   COBRO_REL            varchar(10) not null  comment '',
   COBROINATEC_REL      varchar(10) not null  comment '',
   primary key (COBRO_REL, COBROINATEC_REL)
);

/*==============================================================*/
/* Table: KDSA060A                                              */
/*==============================================================*/
create table KDSA060A
(
   PROSPECTO_REL        varchar(10) not null  comment '',
   NOMBRE_060           varchar(100)  comment '',
   TIPO_060             numeric(1,0)  comment '0.- Natural
             1.- Empresa',
   TELEFONO_060         varchar(50)  comment '',
   CORREO_060           varchar(100)  comment '',
   FECHAINGRESO_060     date  comment '',
   FECHAVENC_060        date  comment '',
   CEDULARUC_060        varchar(20)  comment '',
   NOMBRECONTACTO_060   varchar(100)  comment '',
   TELEFONOCONTACTO_060 varchar(20)  comment '',
   PATRONAL_060         varchar(50)  comment '',
   USUARIO_060          varchar(15)  comment '',
   ACTIVO_060           bool  comment '',
   primary key (PROSPECTO_REL)
);

alter table KDSA060A comment 'Clientes potenciales de KDSA';

/*==============================================================*/
/* Table: KDSA061A                                              */
/*==============================================================*/
create table KDSA061A
(
   MATRICULA_REL        varchar(10) not null  comment '',
   PROSPECTO_REL        varchar(10) not null  comment '',
   primary key (MATRICULA_REL, PROSPECTO_REL)
);

/*==============================================================*/
/* Table: KDSA070A                                              */
/*==============================================================*/
create table KDSA070A
(
   CURSOINATEC_REL      varchar(10) not null  comment '',
   NOMBRE_070           varchar(100)  comment '',
   HORASCLASE_070       numeric(3,0)  comment '',
   CODIGO_070           varchar(20)  comment '',
   ACUERDO_070          varchar(30)  comment '',
   FECHAVENC_070        date  comment '',
   ACTIVO_070           bool  comment '',
   primary key (CURSOINATEC_REL)
);

/*==============================================================*/
/* Table: KDSA080A                                              */
/*==============================================================*/
create table KDSA080A
(
   SEGUIMIENTO_REL      varchar(10) not null  comment '',
   PROSPECTO_REL        varchar(10)  comment '',
   FECHA_080            date  comment '',
   PROXIMOCONTACTO_080  date  comment '',
   OBSERVACIONES_080    varchar(300)  comment '',
   USUARIO_080          varchar(15)  comment '',
   primary key (SEGUIMIENTO_REL)
);

/*==============================================================*/
/* Table: KDSA081A                                              */
/*==============================================================*/
create table KDSA081A
(
   CURSO_REL            varchar(10) not null  comment '',
   SEGUIMIENTO_REL      varchar(10) not null  comment '',
   primary key (CURSO_REL, SEGUIMIENTO_REL)
);

/*==============================================================*/
/* Table: KDSA082A                                              */
/*==============================================================*/
create table KDSA082A
(
   SEGUIMIENTO_REL      varchar(10) not null  comment '',
   DETSEGUIMIENTO_REL   numeric(2,0) not null  comment '',
   CURSO_082            varchar(100)  comment '',
   primary key (SEGUIMIENTO_REL, DETSEGUIMIENTO_REL)
);

/*==============================================================*/
/* Table: KDSA090A                                              */
/*==============================================================*/
create table KDSA090A
(
   PROFORMA_REL         varchar(10) not null  comment '',
   PROSPECTO_REL        varchar(10)  comment '',
   FECHA_090            date  comment '',
   INATEC_090           bool  comment 'Verifica que la Proforma es para INATEC',
   TIPOCAMBIO_090       decimal(10,4)  comment '',
   MONEDA_090           numeric(1,0)  comment '0.- Córdobas
             1.- Dólares',
   DESCUENTO_090        decimal(5,2)  comment '',
   LUGAR_090            varchar(100)  comment '',
   OBSERVACIONES_090    varchar(300)  comment '',
   primary key (PROFORMA_REL)
);

/*==============================================================*/
/* Table: KDSA091A                                              */
/*==============================================================*/
create table KDSA091A
(
   CURSO_REL            varchar(10) not null  comment '',
   PROFORMA_REL         varchar(10) not null  comment '',
   CANTIDAD_091         numeric(2,0)  comment '',
   primary key (CURSO_REL, PROFORMA_REL)
);

/*==============================================================*/
/* Table: KDSA092A                                              */
/*==============================================================*/
create table KDSA092A
(
   PROFORMA_REL         varchar(10) not null  comment '',
   CONSECUTIVO_REL      numeric(2,0) not null  comment '',
   CURSOKDSA_092        varchar(100)  comment '',
   CURSOINATEC_092      varchar(100)  comment '',
   DIASCLASE_092        varchar(100)  comment '',
   HORARIO_092          varchar(100)  comment '',
   FECHAINI_092         date  comment '',
   FECHAFIN_092         date  comment '',
   HORASCLASE_092       numeric(3,0)  comment '',
   CODIGOINATEC_092     varchar(50)  comment '',
   ACUERDO_092          varchar(50)  comment '',
   PRECIO_092           decimal(10,2)  comment '',
   CUPOS_092            numeric(2,0)  comment '',
   TOTAL_092            decimal(10,2)  comment '',
   primary key (PROFORMA_REL, CONSECUTIVO_REL)
);

alter table KDSA092A comment 'Esta tabla guarda los Cursos que aún no se encuentran en KDS';

/*==============================================================*/
/* Table: KDSA093A                                              */
/*==============================================================*/
create table KDSA093A
(
   PROFORMA_REL         varchar(10) not null  comment '',
   DETOBSERVACION_REL   numeric(2,0) not null  comment '',
   OBSERVACION_093      varchar(300)  comment '',
   primary key (PROFORMA_REL, DETOBSERVACION_REL)
);

alter table KDSA093A comment 'Esta tabla NO remplaza OBSERVACIONES_090';

/*==============================================================*/
/* Table: KDSA100A                                              */
/*==============================================================*/
create table KDSA100A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   USUARIO_REL          varchar(15)  comment '',
   NOMBRE_100           varchar(100)  comment '',
   CEDULA_100           varchar(20)  comment '',
   CORREO_100           varchar(100)  comment '',
   TELEFONOS_100        varchar(100)  comment '',
   DIRECCION_100        varchar(250)  comment '',
   ACTIVO_100           bool  comment '',
   primary key (DOCENTE_REL)
);

/*==============================================================*/
/* Table: KDSA101A                                              */
/*==============================================================*/
create table KDSA101A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   DETCURSO_REL         numeric(2,0) not null  comment '',
   DESC_101             varchar(150)  comment '',
   primary key (DOCENTE_REL, DETCURSO_REL)
);

alter table KDSA101A comment 'Cursos o seminarios que el docente puede impartir en KDSA.';

/*==============================================================*/
/* Table: KDSA102A                                              */
/*==============================================================*/
create table KDSA102A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   DETACADEMICO_REL     numeric(2,0) not null  comment '',
   GRADO_102            varchar(100)  comment '',
   TITULO_102           varchar(100)  comment '',
   CENTRO_102           varchar(100)  comment '',
   ANNO_102             varchar(50)  comment '',
   primary key (DOCENTE_REL, DETACADEMICO_REL)
);

/*==============================================================*/
/* Table: KDSA103A                                              */
/*==============================================================*/
create table KDSA103A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   DETESPECIALIZACION_REL numeric(2,0) not null  comment '',
   TITULO_103           varchar(200)  comment '',
   CENTRO_103           varchar(100)  comment '',
   ANNO_103             varchar(50)  comment '',
   primary key (DOCENTE_REL, DETESPECIALIZACION_REL)
);

/*==============================================================*/
/* Table: KDSA104A                                              */
/*==============================================================*/
create table KDSA104A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   DETLABORAL_REL       numeric(2,0) not null  comment '',
   EMPRESA_104          varchar(150)  comment '',
   CARGO_104            varchar(150)  comment '',
   FUNCIONES_104        varchar(400)  comment '',
   PERIODO_104          varchar(150)  comment '',
   primary key (DOCENTE_REL, DETLABORAL_REL)
);

/*==============================================================*/
/* Table: KDSA105A                                              */
/*==============================================================*/
create table KDSA105A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   DETDOCENTE_REL       numeric(2,0) not null  comment '',
   CENTRO_105           varchar(100)  comment '',
   CLASES_105           varchar(300)  comment '',
   PERIODO_105          varchar(150)  comment '',
   primary key (DOCENTE_REL, DETDOCENTE_REL)
);

/*==============================================================*/
/* Table: KDSA106A                                              */
/*==============================================================*/
create table KDSA106A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   DETREFERENCIA_REL    numeric(2,0) not null  comment '',
   NOMBRE_106           varchar(100)  comment '',
   OCUPACION_106        varchar(100)  comment '',
   TELEFONO_106         varchar(50)  comment '',
   CEDULA_106           varchar(20)  comment '',
   primary key (DOCENTE_REL, DETREFERENCIA_REL)
);

/*==============================================================*/
/* Table: KDSA107A                                              */
/*==============================================================*/
create table KDSA107A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   DETADICIONAL_REL     numeric(2,0) not null  comment '',
   INFORMACION_107      varchar(250)  comment '',
   primary key (DOCENTE_REL, DETADICIONAL_REL)
);

/*==============================================================*/
/* Table: KDSA108A                                              */
/*==============================================================*/
create table KDSA108A
(
   DOCENTE_REL          varchar(10) not null  comment '',
   IMAGEN_REL           varchar(50) not null  comment '',
   DESC_108             varchar(150)  comment '',
   RUTA_108             varchar(250)  comment '',
   primary key (DOCENTE_REL, IMAGEN_REL)
);

/*==============================================================*/
/* Table: KDSA110A                                              */
/*==============================================================*/
create table KDSA110A
(
   CFGMODULO_REL        varchar(10) not null  comment '',
   CURSO_110            varchar(100)  comment '',
   primary key (CFGMODULO_REL)
);

/*==============================================================*/
/* Table: KDSA111A                                              */
/*==============================================================*/
create table KDSA111A
(
   CFGMODULO_REL        varchar(10) not null  comment '',
   DETCFGMODULO_REL     numeric(2,0) not null  comment '',
   DESC_111             varchar(200)  comment '',
   primary key (CFGMODULO_REL, DETCFGMODULO_REL)
);

/*==============================================================*/
/* Table: KDSA120A                                              */
/*==============================================================*/
create table KDSA120A
(
   PLANIFICACION_REL    varchar(10) not null  comment '',
   MODULO_REL           varchar(10)  comment '',
   FECHA_120            date  comment '',
   primary key (PLANIFICACION_REL)
);

/*==============================================================*/
/* Table: KDSA121A                                              */
/*==============================================================*/
create table KDSA121A
(
   PLANIFICACION_REL    varchar(10) not null  comment '',
   DETPLANIFICACION_REL numeric(3,0) not null  comment '',
   FECHA_121            date  comment '',
   UNIDAD_121           varchar(10)  comment '',
   CONTENIDO_121        varchar(1000)  comment '',
   OBJETIVOS_121        varchar(1000)  comment '',
   ACTIVIDADES_121      varchar(1000)  comment '',
   RECURSOS_121         varchar(500)  comment '',
   EVALUACION_121       varchar(500)  comment '',
   ESTADO_121           numeric(1,0)  comment '0.- Planificado globalmente
             1.- Planificado en sesión de clase
             2.- Asistencia completada',
   primary key (PLANIFICACION_REL, DETPLANIFICACION_REL)
);

/*==============================================================*/
/* Table: KDSA122A                                              */
/*==============================================================*/
create table KDSA122A
(
   PLANIFICACION_122    varchar(10)  comment '',
   FECHA_122            date  comment '',
   FECHAHORA_122        datetime  comment '',
   ESTADO_122           numeric(1,0)  comment ''
);

alter table KDSA122A comment 'Esta tabla reemplaza ESTADO_121. Ahora se genera un registro';

/*==============================================================*/
/* Table: KDSA130A                                              */
/*==============================================================*/
create table KDSA130A
(
   CLASE_REL            varchar(10) not null  comment '',
   MODULO_REL           varchar(10)  comment '',
   FECHA_130            date  comment '',
   FECHACLASE_130       date  comment '',
   CONTENIDOS_130       varchar(500)  comment '',
   ASIGNACIONES_130     varchar(400)  comment '',
   primary key (CLASE_REL)
);

/*==============================================================*/
/* Table: KDSA131A                                              */
/*==============================================================*/
create table KDSA131A
(
   CLASE_REL            varchar(10) not null  comment '',
   DETOBJETIVOS_REL     numeric(2,0) not null  comment '',
   DESC_131             varchar(400)  comment '',
   primary key (CLASE_REL, DETOBJETIVOS_REL)
);

/*==============================================================*/
/* Table: KDSA132A                                              */
/*==============================================================*/
create table KDSA132A
(
   CLASE_REL            varchar(10) not null  comment '',
   DETACTIVIDADES_REL   numeric(2,0) not null  comment '',
   DESC_132             varchar(400)  comment '',
   primary key (CLASE_REL, DETACTIVIDADES_REL)
);

/*==============================================================*/
/* Table: KDSA133A                                              */
/*==============================================================*/
create table KDSA133A
(
   CLASE_REL            varchar(10) not null  comment '',
   DETMATERIALES_REL    numeric(2,0) not null  comment '',
   DESC_133             varchar(400)  comment '',
   primary key (CLASE_REL, DETMATERIALES_REL)
);

/*==============================================================*/
/* Table: KDSA134A                                              */
/*==============================================================*/
create table KDSA134A
(
   CLASE_REL            varchar(10) not null  comment '',
   APOYO_REL            numeric(5,0) not null  comment '',
   TIPO_134             numeric(1,0)  comment '0.-Teoría
             1.-Ejercicio',
   DESC_134             varchar(200)  comment '',
   RUTA_134             varchar(250)  comment '',
   primary key (CLASE_REL, APOYO_REL)
);

/*==============================================================*/
/* Table: KDSA135A                                              */
/*==============================================================*/
create table KDSA135A
(
   CLASE_REL            varchar(10) not null  comment '',
   SITIO_REL            numeric(2,0) not null  comment '',
   DESC_135             varchar(200)  comment '',
   URL_135              varchar(400)  comment '',
   primary key (CLASE_REL, SITIO_REL)
);

/*==============================================================*/
/* Table: KDSA136A                                              */
/*==============================================================*/
create table KDSA136A
(
   CLASE_REL            varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   ARCHIVOTAREA_REL     decimal(2,0) not null  comment '',
   ARCHIVO_136          varchar(100)  comment '',
   RUTA_136             varchar(250)  comment '',
   primary key (CLASE_REL, MATRICULA_REL, ARCHIVOTAREA_REL)
);

/*==============================================================*/
/* Table: KDSA140A                                              */
/*==============================================================*/
create table KDSA140A
(
   ASISTENCIA_REL       varchar(10) not null  comment '',
   MODULO_REL           varchar(10)  comment '',
   FECHA_140            date  comment '',
   primary key (ASISTENCIA_REL)
);

/*==============================================================*/
/* Table: KDSA141A                                              */
/*==============================================================*/
create table KDSA141A
(
   ASISTENCIA_REL       varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   ESTADO_141           numeric(1,0)  comment '0.- Presente
             1.- Ausente
             2.- Justificado',
   JUSTIFICACION_141    varchar(200)  comment '',
   primary key (ASISTENCIA_REL, MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA150A                                              */
/*==============================================================*/
create table KDSA150A
(
   CALIFICACION_REL     varchar(10) not null  comment '',
   MODULO_REL           varchar(10)  comment '',
   FECHA_150            date  comment '',
   primary key (CALIFICACION_REL)
);

/*==============================================================*/
/* Table: KDSA151A                                              */
/*==============================================================*/
create table KDSA151A
(
   CALIFICACION_REL     varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   PUNTAJE_151          numeric(3,0)  comment '',
   primary key (CALIFICACION_REL, MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA160A                                              */
/*==============================================================*/
create table KDSA160A
(
   REGULACION_REL       varchar(10) not null  comment '',
   CURSO_REL            varchar(10)  comment '',
   FECHAELABORACION_160 datetime  comment '',
   FECHAACTUALIZACION_160 datetime  comment '',
   primary key (REGULACION_REL)
);

/*==============================================================*/
/* Table: KDSA161A                                              */
/*==============================================================*/
create table KDSA161A
(
   MATRICULA_REL        varchar(10) not null  comment '',
   REGULACION_REL       varchar(10)  comment '',
   AUSENCIAS_161        numeric(2,0)  comment '',
   RETIRADO_161         char(2)  comment '',
   RAZONRETIRO_161      varchar(200)  comment '',
   primary key (MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA162A                                              */
/*==============================================================*/
create table KDSA162A
(
   MATRICULA_REL        varchar(10) not null  comment '',
   DETAUSENCIA_REL      numeric(2,0) not null  comment '',
   OBSERVACION_162      varchar(500)  comment '',
   FECHA_162            date  comment '',
   RAZONAUSENCIA_162    varchar(500)  comment '',
   primary key (MATRICULA_REL, DETAUSENCIA_REL)
);

/*==============================================================*/
/* Table: KDSA170A                                              */
/*==============================================================*/
create table KDSA170A
(
   CERTIFICACION_REL    varchar(10) not null  comment '',
   CURSO_REL            varchar(10)  comment '',
   FECHAELABORACION_170 datetime  comment '',
   FECHAACTUALIZACION_170 datetime  comment '',
   MATRICULAINI_M_170   numeric(2,0)  comment '',
   MATRICULAINI_V_170   numeric(2,0)  comment '',
   MATRICULAFIN_M_170   numeric(2,0)  comment '',
   MATRICULAFIN_V_170   numeric(2,0)  comment '',
   DESERCION_V_170      numeric(2,0)  comment '',
   DESERCION_M_170      numeric(2,0)  comment '',
   CERTIFICADOS_M_170   numeric(2,0)  comment '',
   CERTIFICADOS_V_170   numeric(2,0)  comment '',
   primary key (CERTIFICACION_REL)
);

/*==============================================================*/
/* Table: KDSA171A                                              */
/*==============================================================*/
create table KDSA171A
(
   CERTIFICACION_REL    varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   CEDULA_171           bool  comment '',
   ACADEMICOS_171       bool  comment '',
   NOTAS_171            bool  comment '',
   ARANCELCOMPLETO_171  bool  comment '',
   ASISTENCIA_171       numeric(3,0)  comment '',
   ESTADO_171           varchar(15)  comment '',
   TOMO_KDSA_171        varchar(5)  comment '',
   FOLIO_KDSA_171       varchar(5)  comment '',
   ACTA_KDSA_171        varchar(5)  comment '',
   primary key (CERTIFICACION_REL, MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA180A                                              */
/*==============================================================*/
create table KDSA180A
(
   TOMO_REL             varchar(10) not null  comment '',
   APERTURA_180         date  comment '',
   DESCRIPCION_180      varchar(200)  comment '',
   TIPO_180             numeric(1,0)  comment '0.- Seminario
             1.- Curso
             2.- Carrera
             3.- Taller
             4.- Diplomado
             5.- Webinar
             6.- Workshop
             7.- Teambuilding',
   NUMERO_180           numeric(5,0)  comment '',
   ULTIMOFOLIO_180      numeric(5,0)  comment '',
   ULTIMAACTA_180       numeric(5,0)  comment '',
   CERRADO_180          bool  comment '',
   primary key (TOMO_REL)
);

/*==============================================================*/
/* Table: KDSA181A                                              */
/*==============================================================*/
create table KDSA181A
(
   CONSECUTIVO_181      numeric(10,0)  comment ''
);

alter table KDSA181A comment 'Registro único que controla el consecutivo de estudiantes ce';

/*==============================================================*/
/* Table: KDSA190A                                              */
/*==============================================================*/
create table KDSA190A
(
   ACTA_REL             varchar(10) not null  comment '',
   CURSO_REL            varchar(10)  comment '',
   TOMO_REL             varchar(10)  comment '',
   FECHA_190            date  comment '',
   TOMO_190             varchar(5)  comment '',
   ACTA_190             numeric(5,0)  comment '',
   FOLIOINI_190         numeric(5,0)  comment '',
   LINEAINI_190         numeric(3,0)  comment 'Linea de inicio del acta en el folio correspondiente',
   LINEAFIN_190         numeric(3,0)  comment 'Linea de finalización del acta en el folio correspondiente',
   primary key (ACTA_REL)
);

/*==============================================================*/
/* Table: KDSA191A                                              */
/*==============================================================*/
create table KDSA191A
(
   ACTA_REL             varchar(10) not null  comment '',
   MATRICULA_REL        varchar(10) not null  comment '',
   FOLIO_191            numeric(5,0)  comment '',
   REGISTRO_191         numeric(5,0)  comment '',
   VERIFICACION_191     varchar(50)  comment '',
   primary key (ACTA_REL, MATRICULA_REL)
);

/*==============================================================*/
/* Table: KDSA200A                                              */
/*==============================================================*/
create table KDSA200A
(
   DOCCURSO_REL         varchar(10) not null  comment '',
   CURSO_200            varchar(100)  comment '',
   primary key (DOCCURSO_REL)
);

alter table KDSA200A comment 'Aquí se guardan los documentos digitales que deben ir de man';

/*==============================================================*/
/* Table: KDSA201A                                              */
/*==============================================================*/
create table KDSA201A
(
   DOCCURSO_REL         varchar(10) not null  comment '',
   DOCCURSOCONS_REL     numeric(2,0) not null  comment '',
   ARCHIVO_201          varchar(100)  comment '',
   RUTA_201             varchar(250)  comment '',
   primary key (DOCCURSO_REL, DOCCURSOCONS_REL)
);

alter table KDSA005A add constraint FK_KDSA005A_REL_003_0_KDSA003A foreign key (GRUPO_REL)
      references KDSA003A (GRUPO_REL) on delete restrict on update restrict;

alter table KDSA005A add constraint FK_KDSA005A_REL_004_0_KDSA004A foreign key (PAGINA_REL)
      references KDSA004A (PAGINA_REL) on delete restrict on update restrict;

alter table KDSA006A add constraint FK_KDSA006A_KDSA006A_KDSA003A foreign key (GRUPO_REL)
      references KDSA003A (GRUPO_REL) on delete restrict on update restrict;

alter table KDSA006A add constraint FK_KDSA006A_KDSA006A2_KDSA002A foreign key (USUARIO_REL)
      references KDSA002A (USUARIO_REL) on delete restrict on update restrict;

alter table KDSA007A add constraint FK_KDSA007A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA010A add constraint FK_KDSA010A_REL_060_0_KDSA060A foreign key (PROSPECTO_REL)
      references KDSA060A (PROSPECTO_REL) on delete restrict on update restrict;

alter table KDSA011A add constraint FK_KDSA011A_REL_010_0_KDSA010A foreign key (ESTUDIANTE_REL)
      references KDSA010A (ESTUDIANTE_REL) on delete restrict on update restrict;

alter table KDSA020A add constraint FK_KDSA020A_REL_070_0_KDSA070A foreign key (CURSOINATEC_REL)
      references KDSA070A (CURSOINATEC_REL) on delete restrict on update restrict;

alter table KDSA021A add constraint FK_KDSA021A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA021A add constraint FK_KDSA021A_REL_100_0_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA022A add constraint FK_KDSA022A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA023A add constraint FK_KDSA023A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA023A add constraint FK_KDSA023A_REL_100_0_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA024A add constraint FK_KDSA024A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA030A add constraint FK_KDSA030A_REL_010_0_KDSA010A foreign key (ESTUDIANTE_REL)
      references KDSA010A (ESTUDIANTE_REL) on delete restrict on update restrict;

alter table KDSA030A add constraint FK_KDSA030A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA031A add constraint FK_KDSA031A_REL_030_0_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA041A add constraint FK_KDSA041A_REL_040_0_KDSA040A foreign key (PAGO_REL)
      references KDSA040A (PAGO_REL) on delete restrict on update restrict;

alter table KDSA041A add constraint FK_KDSA041A_REL_051_0_KDSA051A foreign key (COBRO_REL, MATRICULA_REL)
      references KDSA051A (COBRO_REL, MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA042A add constraint FK_KDSA042A_REL_040_0_KDSA040A foreign key (PAGO_REL)
      references KDSA040A (PAGO_REL) on delete restrict on update restrict;

alter table KDSA042A add constraint FK_KDSA042A_REL_052_0_KDSA052A foreign key (COBRO_REL)
      references KDSA052A (COBRO_REL) on delete restrict on update restrict;

alter table KDSA043A add constraint FK_KDSA043A_REL_040_0_KDSA040A foreign key (PAGO_REL)
      references KDSA040A (PAGO_REL) on delete restrict on update restrict;

alter table KDSA043A add constraint FK_KDSA043A_REL_051_0_KDSA051A foreign key (COBRO_REL, MATRICULA_REL)
      references KDSA051A (COBRO_REL, MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA043A add constraint FK_KDSA043A_REL_054_0_KDSA054A foreign key (COBROINATEC_REL)
      references KDSA054A (COBROINATEC_REL) on delete restrict on update restrict;

alter table KDSA050A add constraint FK_KDSA050A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA050A add constraint FK_KDSA050A_REL_050_0_KDSA050A foreign key (KDS_COBRO_REL)
      references KDSA050A (COBRO_REL) on delete restrict on update restrict;

alter table KDSA051A add constraint FK_KDSA051A_REL_030_0_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA051A add constraint FK_KDSA051A_REL_050_0_KDSA050A foreign key (COBRO_REL)
      references KDSA050A (COBRO_REL) on delete restrict on update restrict;

alter table KDSA052A add constraint FK_KDSA052A_REL_050_0_KDSA050A foreign key (COBRO_REL)
      references KDSA050A (COBRO_REL) on delete restrict on update restrict;

alter table KDSA053A add constraint FK_KDSA053A_KDSA053A_KDSA052A foreign key (COBRO_REL)
      references KDSA052A (COBRO_REL) on delete restrict on update restrict;

alter table KDSA053A add constraint FK_KDSA053A_KDSA053A2_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA055A add constraint FK_KDSA055A_KDSA055A_KDSA050A foreign key (COBRO_REL)
      references KDSA050A (COBRO_REL) on delete restrict on update restrict;

alter table KDSA055A add constraint FK_KDSA055A_KDSA055A2_KDSA054A foreign key (COBROINATEC_REL)
      references KDSA054A (COBROINATEC_REL) on delete restrict on update restrict;

alter table KDSA061A add constraint FK_KDSA061A_KDSA061A_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA061A add constraint FK_KDSA061A_KDSA061A2_KDSA060A foreign key (PROSPECTO_REL)
      references KDSA060A (PROSPECTO_REL) on delete restrict on update restrict;

alter table KDSA080A add constraint FK_KDSA080A_REL_060_0_KDSA060A foreign key (PROSPECTO_REL)
      references KDSA060A (PROSPECTO_REL) on delete restrict on update restrict;

alter table KDSA081A add constraint FK_KDSA081A_KDSA081A_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA081A add constraint FK_KDSA081A_KDSA081A2_KDSA080A foreign key (SEGUIMIENTO_REL)
      references KDSA080A (SEGUIMIENTO_REL) on delete restrict on update restrict;

alter table KDSA082A add constraint FK_KDSA082A_REL_080_0_KDSA080A foreign key (SEGUIMIENTO_REL)
      references KDSA080A (SEGUIMIENTO_REL) on delete restrict on update restrict;

alter table KDSA090A add constraint FK_KDSA090A_REL_060_0_KDSA060A foreign key (PROSPECTO_REL)
      references KDSA060A (PROSPECTO_REL) on delete restrict on update restrict;

alter table KDSA091A add constraint FK_KDSA091A_REL_020_0_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA091A add constraint FK_KDSA091A_REL_090_0_KDSA090A foreign key (PROFORMA_REL)
      references KDSA090A (PROFORMA_REL) on delete restrict on update restrict;

alter table KDSA092A add constraint FK_KDSA092A_REL_090_0_KDSA090A foreign key (PROFORMA_REL)
      references KDSA090A (PROFORMA_REL) on delete restrict on update restrict;

alter table KDSA093A add constraint FK_KDSA093A_REL_090_0_KDSA090A foreign key (PROFORMA_REL)
      references KDSA090A (PROFORMA_REL) on delete restrict on update restrict;

alter table KDSA100A add constraint FK_KDSA100A_REL_002_1_KDSA002A foreign key (USUARIO_REL)
      references KDSA002A (USUARIO_REL) on delete restrict on update restrict;

alter table KDSA101A add constraint FK_KDSA101A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA102A add constraint FK_KDSA102A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA103A add constraint FK_KDSA103A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA104A add constraint FK_KDSA104A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA105A add constraint FK_KDSA105A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA106A add constraint FK_KDSA106A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA107A add constraint FK_KDSA107A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA108A add constraint FK_KDSA108A_REL_100_1_KDSA100A foreign key (DOCENTE_REL)
      references KDSA100A (DOCENTE_REL) on delete restrict on update restrict;

alter table KDSA111A add constraint FK_KDSA111A_REL_110_1_KDSA110A foreign key (CFGMODULO_REL)
      references KDSA110A (CFGMODULO_REL) on delete restrict on update restrict;

alter table KDSA120A add constraint FK_KDSA120A_REL_021_1_KDSA021A foreign key (MODULO_REL)
      references KDSA021A (MODULO_REL) on delete restrict on update restrict;

alter table KDSA121A add constraint FK_KDSA121A_REL_120_1_KDSA120A foreign key (PLANIFICACION_REL)
      references KDSA120A (PLANIFICACION_REL) on delete restrict on update restrict;

alter table KDSA130A add constraint FK_KDSA130A_REL_021_1_KDSA021A foreign key (MODULO_REL)
      references KDSA021A (MODULO_REL) on delete restrict on update restrict;

alter table KDSA131A add constraint FK_KDSA131A_REL_130_1_KDSA130A foreign key (CLASE_REL)
      references KDSA130A (CLASE_REL) on delete restrict on update restrict;

alter table KDSA132A add constraint FK_KDSA132A_REL_130_1_KDSA130A foreign key (CLASE_REL)
      references KDSA130A (CLASE_REL) on delete restrict on update restrict;

alter table KDSA133A add constraint FK_KDSA133A_REL_130_1_KDSA130A foreign key (CLASE_REL)
      references KDSA130A (CLASE_REL) on delete restrict on update restrict;

alter table KDSA134A add constraint FK_KDSA134A_REL_130_1_KDSA130A foreign key (CLASE_REL)
      references KDSA130A (CLASE_REL) on delete restrict on update restrict;

alter table KDSA135A add constraint FK_KDSA135A_REL_130_1_KDSA130A foreign key (CLASE_REL)
      references KDSA130A (CLASE_REL) on delete restrict on update restrict;

alter table KDSA136A add constraint FK_KDSA136A_REL_030_1_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA136A add constraint FK_KDSA136A_REL_130_1_KDSA130A foreign key (CLASE_REL)
      references KDSA130A (CLASE_REL) on delete restrict on update restrict;

alter table KDSA140A add constraint FK_KDSA140A_REL_021_1_KDSA021A foreign key (MODULO_REL)
      references KDSA021A (MODULO_REL) on delete restrict on update restrict;

alter table KDSA141A add constraint FK_KDSA141A_REL_030_1_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA141A add constraint FK_KDSA141A_REL_140_1_KDSA140A foreign key (ASISTENCIA_REL)
      references KDSA140A (ASISTENCIA_REL) on delete restrict on update restrict;

alter table KDSA150A add constraint FK_KDSA150A_REL_021_1_KDSA021A foreign key (MODULO_REL)
      references KDSA021A (MODULO_REL) on delete restrict on update restrict;

alter table KDSA151A add constraint FK_KDSA151A_REL_030_1_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA151A add constraint FK_KDSA151A_REL_150_1_KDSA150A foreign key (CALIFICACION_REL)
      references KDSA150A (CALIFICACION_REL) on delete restrict on update restrict;

alter table KDSA160A add constraint FK_KDSA160A_REL_020_1_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA161A add constraint FK_KDSA161A_REL_030_1_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA161A add constraint FK_KDSA161A_REL_160_1_KDSA160A foreign key (REGULACION_REL)
      references KDSA160A (REGULACION_REL) on delete restrict on update restrict;

alter table KDSA162A add constraint FK_KDSA162A_REL_161_1_KDSA161A foreign key (MATRICULA_REL)
      references KDSA161A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA170A add constraint FK_KDSA170A_REL_020_1_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA171A add constraint FK_KDSA171A_REL_030_1_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA171A add constraint FK_KDSA171A_REL_170_1_KDSA170A foreign key (CERTIFICACION_REL)
      references KDSA170A (CERTIFICACION_REL) on delete restrict on update restrict;

alter table KDSA190A add constraint FK_KDSA190A_REL_020_1_KDSA020A foreign key (CURSO_REL)
      references KDSA020A (CURSO_REL) on delete restrict on update restrict;

alter table KDSA190A add constraint FK_KDSA190A_REL_180_1_KDSA180A foreign key (TOMO_REL)
      references KDSA180A (TOMO_REL) on delete restrict on update restrict;

alter table KDSA191A add constraint FK_KDSA191A_REL_030_1_KDSA030A foreign key (MATRICULA_REL)
      references KDSA030A (MATRICULA_REL) on delete restrict on update restrict;

alter table KDSA191A add constraint FK_KDSA191A_REL_190_1_KDSA190A foreign key (ACTA_REL)
      references KDSA190A (ACTA_REL) on delete restrict on update restrict;

alter table KDSA201A add constraint FK_KDSA201A_REL_200_2_KDSA200A foreign key (DOCCURSO_REL)
      references KDSA200A (DOCCURSO_REL) on delete restrict on update restrict;

