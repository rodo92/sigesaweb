USE [SIGH]
GO

-- habilitar si no existe la columna en la tabla
--ALTER TABLE [dbo].[ListBarGrupos] ADD IconosWeb varchar(100)
drop table [dbo].[CajaFacturacionDetalle]
drop table [dbo].[CajaFacturacion]

-- ELIMINAR PROCEDIMIENTOS
DECLARE @id varchar(255) -- used to store the table name to drop
DECLARE @dropCommand varchar(255) -- used to store the t-sql command to drop the table

DECLARE tableCursor CURSOR FOR 
    SELECT name FROM sys.objects WHERE type = 'P' AND name like '%SIGESA_%'

OPEN tableCursor 
FETCH next FROM tableCursor INTO @id 

WHILE @@fetch_status=0 
BEGIN 
	-- Prepare the sql statement
    SET @dropcommand = N'DROP PROCEDURE ' + @id 
    -- print @dropCommand -- just a debug check
    -- Execute the drop
    EXECUTE(@dropcommand) 
    
    -- move to next record
    FETCH next FROM tableCursor INTO @id 
END 

CLOSE tableCursor 
DEALLOCATE tableCursor

USE [SIGH]
GO
/****** Object:  Table [dbo].[CajaFacturacion]    Script Date: 13/12/2018 3:23:10 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[CajaFacturacion](
	[IdCajaFacturacion] [int] IDENTITY(1,1) NOT NULL,
	[FechaCobranza] [datetime] NULL,
	[NroSerie] [nchar](4) NULL,
	[NroDocumento] [nchar](10) NULL,
	[Ruc] [nchar](11) NULL,
	[RazonSocial] [nvarchar](200) NULL,
	[IdTipoComprobante] [char](1) NULL,
	[IdCajero] [int] NULL,
	[Subtotal] [money] NULL,
	[IGV] [money] NULL,
	[Total] [money] NULL,
	[IdPaciente] [int] NULL,
	[Observacion1] [nvarchar](200) NULL,
	[Observacion2] [nvarchar](200) NULL,
	[Concepto] [text] NULL,
 CONSTRAINT [PK_CajaFacturacion] PRIMARY KEY CLUSTERED 
(
	[IdCajaFacturacion] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[CajaFacturacionDetalle]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[CajaFacturacionDetalle](
	[IdCajaFacturacion] [int] NULL,
	[IdCuentaAtencion] [int] NULL,
	[IdPartida] [nchar](10) NULL,
	[Codigo] [nchar](10) NULL,
	[Cantidad] [int] NULL,
	[ValorUnitario] [money] NULL,
	[SubTotal] [money] NULL,
	[IGV] [money] NULL,
	[Total] [money] NULL,
	[Descripcion] [text] NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
ALTER TABLE [dbo].[CajaFacturacionDetalle]  WITH CHECK ADD  CONSTRAINT [FK_CajaFacturacionDetalle_CajaFacturacion] FOREIGN KEY([IdCajaFacturacion])
REFERENCES [dbo].[CajaFacturacion] ([IdCajaFacturacion])
GO
ALTER TABLE [dbo].[CajaFacturacionDetalle] CHECK CONSTRAINT [FK_CajaFacturacionDetalle_CajaFacturacion]
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_Apertura_Caja]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[SIGESA_Apertura_Caja]
(
	@FechaApertura datetime,
	@EstadoLote char,
	@IdCaja int,
	@IdTurno int,
	@TotalCobrado char,
	@IdCajero int
)
as
declare @a int
insert into CajaGestion(FechaApertura,EstadoLote,IdCaja,IdTurno,TotalCobrado,IdCajero)
values(@FechaApertura,@EstadoLote,@IdCaja,@IdTurno,@TotalCobrado,@IdCajero)
set @a = scope_identity()
select @a as 'IdGestionCaja'
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_CAJA_DATOS_X_CODIGO_PARA_FACTURACION]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_CAJA_DATOS_X_CODIGO_PARA_FACTURACION]
(
	@Serie nvarchar(4),
	@NroComprobante nvarchar(8),
	@nroOrden INT
)
/*
	DECLARE @Serie nvarchar(4)
	DECLARE @NroComprobante nvarchar(8)
	DECLARE @nroOrden INT =0
SET @Serie = 'BF02'
SET @NroComprobante = '01092994'
*/

--exec SIGESA_CAJA_DATOS_X_CODIGO_PARA_FACTURACION 'BF02','01092994',''

AS
DECLARE @finicial date
DECLARE @ffinal date

SET @finicial = '20181001'
SET @ffinal = '20181231'
SELECT DISTINCT
	ccp.NroSerie + '-' + ccp.NroDocumento Comprobante
	,fop.idOrden
	,ccp.IdCuentaAtencion cuenta
	,p.IdPaciente
	,RUC
	,ccp.RazonSocial
	,ccp.SubTotal
	,ccp.IGV
	,ccp.Total
	,ccp.FechaCobranza
	,ccp.IdEstadoComprobante idEstado
	,cec.Descripcion estado
	,fpp.Codigo CodigoPresupuestal
	,fpp.Descripcion Nombre
	,fcs.Codigo
	,fcs.Nombre Producto
	,fsp.Cantidad
	,CASE WHEN fcs.Codigo LIKE 'PRT%' THEN fsp.Precio/1.18  ELSE fsp.Precio END Precio
	,fsp.Total AS TotalUnitario
	,CASE WHEN fcs.Codigo LIKE 'PRT%' THEN fsp.Precio/1.18*0.18 ELSE 0 end AS 'IGVUNITARIO' --   , * 
FROM dbo.FacturacionServicioDespacho fsd
INNER JOIN FactOrdenServicio fos	ON fsd.idOrden = fos.idOrden
LEFT JOIN CajaComprobantesPago ccp	ON fos.IdCuentaAtencion = ccp.IdCuentaAtencion
FULL OUTER JOIN FactOrdenServicioPagos fop	ON ccp.IdComprobantePago = fop.IdComprobantePago
FULL OUTER JOIN FacturacionServicioPagos fsp	ON fop.idOrdenPago = fsp.idOrdenPago
INNER JOIN FactCatalogoServicios fcs	ON fsp.IdProducto = fcs.IdProducto
INNER JOIN FactPartidasPresupuestales fpp	ON fcs.IdPartida = fpp.IdPartidaPresupuestal
LEFT JOIN Pacientes p	ON ccp.IdPaciente = p.IdPaciente
LEFT JOIN CajaEstadosComprobante cec	ON ccp.IdEstadoComprobante = cec.IdEstadoComprobante
WHERE ccp.idFarmacia IS NULL
AND CONVERT(DATE, ccp.FechaCobranza, 103) BETWEEN @finicial AND @ffinal
AND ccp.NroSerie + '-' + ccp.NroDocumento = ISNULL(@Serie + '-' + @NroComprobante, ccp.NroSerie + '-' + ccp.NroDocumento)
AND fop.idOrden = ISNULL(@nroOrden, fop.idOrden)

union all

SELECT
	ccp.NroSerie + '-' + ccp.NroDocumento COLLATE database_default Comprobante
	,fob.idOrden idOrden
	,ccp.IdCuentaAtencion cuenta
	,p.IdPaciente
	,RUC
	,ccp.RazonSocial
	,ROUND(ccp.SubTotal / 1.18	, 2) SubTotal
	,ROUND((ccp.SubTotal / 1.18)*0.18	, 2) IGV
	,ccp.Total
	,ccp.FechaCobranza
	,ccp.IdEstadoComprobante idEstado
	,cec.Descripcion estado
	,fpp.Codigo CodigoPresupuestal
	,fpp.Descripcion Nombre
	,fcbi.Codigo
	,fcbi.Nombre COLLATE database_default Producto
	,fbp.CantidadPagar Cantidad
	,fbp.PrecioVenta/1.18  Precio
	--	,CASE WHEN fcs.Codigo LIKE 'PRT%' THEN fsp.Precio/1.18  ELSE fsp.Precio END Precio
	,fbp.TotalPagar AS TotalUnitario
	,fbp.PrecioVenta/1.18*0.18 AS 'IGVUNITARIO'--  , * 
	
FROM CajaComprobantesPago ccp
FULL OUTER JOIN FactOrdenesBienes fob	ON ccp.IdComprobantePago = fob.IdComprobantePago
FULL OUTER JOIN FacturacionBienesPagos fbp	ON fob.idOrden = fbp.idOrden
FULL OUTER JOIN FactCatalogoBienesInsumos fcbi	ON fbp.IdProducto = fcbi.IdProducto
INNER JOIN FactPartidasPresupuestales fpp	ON fcbi.IdPartida = fpp.IdPartidaPresupuestal
LEFT JOIN Pacientes p	ON ccp.IdPaciente = p.IdPaciente
LEFT JOIN CajaEstadosComprobante cec	ON ccp.IdEstadoComprobante = cec.IdEstadoComprobante
WHERE ccp.idFarmacia IS NOT NULL
AND CONVERT(DATE, ccp.FechaCobranza, 103) BETWEEN @finicial AND @ffinal
AND ccp.NroSerie + '-' + ccp.NroDocumento = ISNULL(@Serie + '-' + @NroComprobante, ccp.NroSerie + '-' + ccp.NroDocumento)
AND fob.idOrden = ISNULL(@nroOrden, fob.idOrden)
 UNION all
 
SELECT
	m.DocumentoNumero Comprobante
	,'' idorden
	,fmv.idCuentaAtencion cuenta
	,p.IdPaciente
	,NULL ruc
	,p.ApellidoPaterno +' '+ p.ApellidoMaterno+' '+p.PrimerNombre COLLATE database_default  RazonSocial
	,ROUND(m.Total/1.18,2) SubTotal	
	,ROUND(m.Total/1.18*0.18,2) IGV	
	,m.Total Total
	,m.FechaCreacion FechaCobranza
	,m.idEstadoMovimiento idestado
	,farmEstadosMovimientos.Estado as estado
	,fpp.Codigo CodigoPresupuestal
	,fpp.Descripcion COLLATE database_default   Nombre
	,fcbi.Codigo
	,fcbi.Nombre COLLATE database_default  Producto 
	,fmvd.cantidad
	,fmvd.Precio/1.18 Precio
 	,fmvd.Total as TotalUnitario
	, fmvd.Precio/1.18*0.18 as 'IGVUNITARIO'
FROM dbo.TiposFinanciamiento
RIGHT OUTER JOIN dbo.Atenciones	a						ON dbo.TiposFinanciamiento.idTipoFinanciamiento = a.idFormaPago
RIGHT OUTER JOIN dbo.farmMovimiento m
INNER JOIN dbo.farmMovimientoVentas fmv					ON m.MovNumero = fmv.MovNumero	AND m.MovTipo = fmv.MovTipo	
														ON a.IdCuentaAtencion = fmv.IdCuentaAtencion														
LEFT OUTER JOIN dbo.farmEstadosMovimientos				ON m.idEstadoMovimiento = dbo.farmEstadosMovimientos.idEstadoMovimiento
LEFT OUTER JOIN dbo.farmAlmacen	fa						ON m.idAlmacenOrigen = fa.idAlmacen
LEFT OUTER JOIN dbo.Empleados e							ON m.IdUsuario = e.IdEmpleado
RIGHT OUTER JOIN dbo.farmMovimientoVentasDetalle fmvd 	ON fmv.MovNumero = fmvd.MovNumero	AND fmv.MovTipo = fmvd.MovTipo
LEFT OUTER JOIN dbo.FactCatalogoBienesInsumos fcbi		ON fmvd.idProducto = fcbi.idProducto
LEFT JOIN Pacientes p ON a.IdPaciente = p.IdPaciente
LEFT JOIN FactPartidasPresupuestales fpp ON fcbi.IdPartida = fpp.IdPartidaPresupuestal
WHERE     
m.idEstadoMovimiento = 1
AND convert(DATE,m.fechaCreacion,103) BETWEEN @finicial and @ffinal 
AND m.DocumentoNumero = ISNULL(@Serie+'-'+@NroComprobante,DocumentoNumero)
--AND fob.idOrden = isnull(@nroOrden , fob.idOrden)

UNION ALL


SELECT DISTINCT
	'' comprobante
	,fos.IdOrden
	,fos.idCuentaAtencion cuenta
	,p.IdPaciente
	,'' ruc
	,(p.ApellidoPaterno + ' ' + p.ApellidoMaterno + ' ' + p.PrimerNombre) COLLATE database_default RazonSocial
	, fsd.Total subtotalcab
	,0 igv
	,fsd.Total totalcab
	,fos.FechaCreacion
	,fos.idEstadoFacturacion idestado
	,ef.Descripcion Estado
	,fpp.Codigo
	,fpp.Descripcion nombre
	,fcs.Codigo
	,fcs.nombre
	, fsd.Cantidad Cantidad
	--, fsd.Precio Precio	
	,CASE WHEN fcs.Codigo LIKE 'PRT%' THEN fsd.Precio/1.18  ELSE fsd.Precio END Precio
	, fsd.Total AS TotalUnitario
	,CASE WHEN fcs.Codigo LIKE 'PRT%' THEN fsd.Precio/1.18 *0.18 ELSE fsd.Precio END AS 'IGVUNITARIO'
FROM dbo.FacturacionServicioDespacho fsd
INNER JOIN dbo.FactOrdenServicio fos	ON fsd.IdOrden = fos.IdOrden
INNER JOIN dbo.FactCatalogoServicios fcs	ON fsd.IdProducto = fcs.IdProducto
INNER JOIN FactPartidasPresupuestales fpp	ON fcs.IdPartida = fpp.IdPartidaPresupuestal
LEFT JOIN EstadosFacturacion ef	ON fos.idEstadoFacturacion = ef.idEstadoFacturacion
LEFT JOIN Pacientes p	ON fos.IdPaciente = p.IdPaciente
WHERE --fos.idFuenteFinanciamiento!=1 AND 
CONVERT(DATE, fos.FechaCreacion, 103) BETWEEN @finicial AND @ffinal
--AND m.DocumentoNumero = ISNULL(@Serie+'-'+@NroComprobante,DocumentoNumero)
AND fos.IdOrden = ISNULL(@nroOrden, fos.IdOrden)
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_CajaCajaSeleccionarTodos]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_CajaCajaSeleccionarTodos]
AS

Select IdCaja, Descripcion from CajaCaja
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_CajaCajaTipoComprobante]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_CajaCajaTipoComprobante]
AS
SELECT	CajaTiposComprobante.IdTipoComprobante,
		CajaTiposComprobante.Descripcion
FROM	CajaTiposComprobante
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_CajaFacturacionInsertar]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--SELECT * FROM CajaFacturacion
CREATE PROCEDURE [dbo].[SIGESA_CajaFacturacionInsertar]
(
	--@IdCajaFacturacion INT OUTPUT,
	@FechaCobranza nchar(10)= NULL,
	@NroSerie nchar(4)= NULL,
	@NroDocumento nchar(10)= NULL,
	@Ruc nchar(11)= NULL,
	@RazonSocial nvarchar(200)= NULL,
	@IdTipoComprobante char= NULL,
	@IdCajero INT= NULL,
	@Subtotal MONEY= NULL,
	@IGV MONEY= NULL,
	@Total MONEY= NULL,
	@IdPaciente INT= NULL,
	@Observacion1 nvarchar(200)= NULL,
	@Observacion2 nvarchar(200)= NULL
	)
	as
BEGIN  
INSERT INTO CajaFacturacion ( FechaCobranza, NroSerie, NroDocumento, Ruc, RazonSocial, IdTipoComprobante, IdCajero, Subtotal, IGV, Total, IdPaciente, Observacion1, Observacion2)
			  VALUES ( @FechaCobranza, @NroSerie, @NroDocumento, @Ruc, @RazonSocial, @IdTipoComprobante, @IdCajero, @Subtotal, @IGV, @Total, @IdPaciente, @Observacion1, @Observacion2);
--SET @IdCajaFacturacion = @@Identity
END 
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_DATOS_FACTURACION_X_CUENTA_CABECERA]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_DATOS_FACTURACION_X_CUENTA_CABECERA]
(
@lnIdCuentaAtencion int
)
AS

SELECT     dbo.Atenciones.FechaIngreso, dbo.Atenciones.IdCuentaAtencion, dbo.Atenciones.IdTipoServicio, dbo.Atenciones.IdFormaPago, 
                      dbo.Pacientes.ApellidoPaterno, dbo.Pacientes.ApellidoMaterno, dbo.Pacientes.PrimerNombre, dbo.Pacientes.NroHistoriaClinica, 
                      dbo.Atenciones.IdPaciente, dbo.TiposFinanciamiento.tipoVenta, dbo.Atenciones.FechaEgreso, dbo.FacturacionCuentasAtencion.IdEstado, 
                      dbo.EstadosCuenta.Descripcion AS estadoCta, dbo.Atenciones.idFuenteFinanciamiento, dbo.Atenciones.IdServicioIngreso, 
                      dbo.FuentesFinanciamiento.Descripcion AS dFuenteFinanciamiento, dbo.Pacientes.IdTipoNumeracion, dbo.Atenciones.IdAtencion, 
                      dbo.Atenciones.IdCamaEgreso, dbo.TiposServicio.Descripcion AS dTipoServicio, dbo.Atenciones.idEstadoAtencion, dbo.Atenciones.EsPacienteExterno, 
                      dbo.TiposFinanciamiento.Descripcion AS dTipoFinanciamiento, dbo.Atenciones.IdCondicionAlta, dbo.Pacientes.IdTipoSexo, 
                      dbo.AtencionesDatosAdicionales.IdTipoReferenciaOrigen, dbo.AtencionesDatosAdicionales.NroReferenciaDestino, dbo.Pacientes.SegundoNombre
FROM         dbo.Atenciones INNER JOIN
                      dbo.FacturacionCuentasAtencion ON dbo.Atenciones.IdCuentaAtencion = dbo.FacturacionCuentasAtencion.IdCuentaAtencion LEFT OUTER JOIN
                      dbo.AtencionesDatosAdicionales ON dbo.Atenciones.IdAtencion = dbo.AtencionesDatosAdicionales.idAtencion LEFT OUTER JOIN
                      dbo.TiposServicio ON dbo.Atenciones.IdTipoServicio = dbo.TiposServicio.IdTipoServicio LEFT OUTER JOIN
                      dbo.FuentesFinanciamiento ON dbo.Atenciones.idFuenteFinanciamiento = dbo.FuentesFinanciamiento.IdFuenteFinanciamiento LEFT OUTER JOIN
                      dbo.EstadosCuenta ON dbo.FacturacionCuentasAtencion.IdEstado = dbo.EstadosCuenta.IdEstado LEFT OUTER JOIN
                      dbo.TiposFinanciamiento ON dbo.Atenciones.IdFormaPago = dbo.TiposFinanciamiento.IdTipoFinanciamiento LEFT OUTER JOIN
                      dbo.Pacientes ON dbo.Atenciones.IdPaciente = dbo.Pacientes.IdPaciente
WHERE     dbo.Atenciones.IdCuentaAtencion =@lnIdCuentaAtencion
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_DATOS_FACTURACION_X_CUENTA_DETALLE]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_DATOS_FACTURACION_X_CUENTA_DETALLE]
(
	@cuenta INT 
)  
AS

WITH liquidacion AS ( 

SELECT
dbo.FactOrdenServicio.FechaCreacion
,dbo.FactOrdenServicio.IdCuentaAtencion
,'' DocumentoNumero
,fpp.Codigo codPartida, fpp.Descripcion Partida
,dbo.FactCatalogoServicios.Codigo
,dbo.FacturacionServicioDespacho.IdProducto
,dbo.FactCatalogoServicios.Nombre
,dbo.FacturacionServicioDespacho.cantidad
,dbo.FacturacionServicioDespacho.Precio PrecioUnitario
,dbo.FacturacionServicioDespacho.Precio AS  SubTotal
,0 AS  Impuesto
,dbo.FacturacionServicioDespacho.Total
FROM dbo.FacturacionServicioDespacho
INNER JOIN dbo.FactOrdenServicio		ON dbo.FacturacionServicioDespacho.IdOrden = dbo.FactOrdenServicio.IdOrden
INNER JOIN dbo.FactCatalogoServicios	ON dbo.FacturacionServicioDespacho.IdProducto = dbo.FactCatalogoServicios.IdProducto
LEFT OUTER JOIN dbo.TiposFinanciamiento	ON dbo.FactOrdenServicio.idTipoFinanciamiento = dbo.TiposFinanciamiento.idTipoFinanciamiento
LEFT OUTER JOIN dbo.EstadosFacturacion	ON dbo.FactOrdenServicio.IdEstadoFacturacion = dbo.EstadosFacturacion.IdEstadoFacturacion
LEFT OUTER JOIN dbo.Atenciones			ON dbo.FactOrdenServicio.IdCuentaAtencion = dbo.Atenciones.IdCuentaAtencion
LEFT JOIN FactPartidasPresupuestales fpp ON FactCatalogoServicios.IdPartida = fpp.IdPartidaPresupuestal
WHERE dbo.FactOrdenServicio.IdCuentaAtencion = @cuenta

union all

SELECT
dbo.farmMovimiento.FechaCreacion
,dbo.farmMovimientoVentas.IdCuentaAtencion
,dbo.farmMovimiento.DocumentoNumero COLLATE database_default
,fpp.Codigo codPartida, fpp.Descripcion Partida
,dbo.FactCatalogoBienesInsumos.Codigo
,dbo.farmMovimientoVentasDetalle.IdProducto
,dbo.FactCatalogoBienesInsumos.Nombre COLLATE database_default
,dbo.farmMovimientoVentasDetalle.cantidad
,ROUND(dbo.farmMovimientoVentasDetalle.Precio,2) PrecioUnitario
,ROUND(dbo.farmMovimientoVentasDetalle.Total*(SELECT 100-p.ValorTexto FROM Parametros p WHERE p.IdParametro =221)/100,2) AS  SubTotal
,ROUND(dbo.farmMovimientoVentasDetalle.Total*(SELECT p.ValorTexto FROM Parametros p WHERE p.IdParametro =221)/100,2) AS  Impuesto
,ROUND(dbo.farmMovimientoVentasDetalle.Total,2) Total
FROM dbo.TiposFinanciamiento
RIGHT OUTER JOIN dbo.Atenciones						ON dbo.TiposFinanciamiento.idTipoFinanciamiento = dbo.Atenciones.IdFormaPago
RIGHT OUTER JOIN dbo.farmMovimiento
INNER JOIN dbo.farmMovimientoVentas					ON dbo.farmMovimiento.MovNumero = dbo.farmMovimientoVentas.MovNumero		AND dbo.farmMovimiento.MovTipo = dbo.farmMovimientoVentas.MovTipo
													ON dbo.Atenciones.IdCuentaAtencion = dbo.farmMovimientoVentas.IdCuentaAtencion
LEFT OUTER JOIN dbo.farmEstadosMovimientos			ON dbo.farmMovimiento.idEstadoMovimiento = dbo.farmEstadosMovimientos.idEstadoMovimiento
LEFT OUTER JOIN dbo.farmAlmacen						ON dbo.farmMovimiento.idAlmacenOrigen = dbo.farmAlmacen.idAlmacen
LEFT OUTER JOIN dbo.Empleados						ON dbo.farmMovimiento.IdUsuario = dbo.Empleados.IdEmpleado
RIGHT OUTER JOIN dbo.farmMovimientoVentasDetalle	ON dbo.farmMovimientoVentas.MovNumero = dbo.farmMovimientoVentasDetalle.MovNumero		AND dbo.farmMovimientoVentas.MovTipo = dbo.farmMovimientoVentasDetalle.MovTipo
LEFT OUTER JOIN dbo.FactCatalogoBienesInsumos		ON dbo.farmMovimientoVentasDetalle.IdProducto = dbo.FactCatalogoBienesInsumos.IdProducto
LEFT JOIN FactPartidasPresupuestales fpp ON FactCatalogoBienesInsumos.IdPartida = fpp.IdPartidaPresupuestal
WHERE dbo.farmMovimientoVentas.IdCuentaAtencion = @cuenta
AND dbo.farmMovimiento.idEstadoMovimiento = 1

union all

SELECT
FechaOcupacion FechaCreacion
,IdCuentaAtencion
,'' documentoNumero
,fpp.Codigo codPartida, fpp.Descripcion Partida
,fcs.Codigo
,fcs.IdProducto
,dbo.Servicios.Nombre COLLATE database_default Nombre
,DATEDIFF(DAY , FechaOcupacion,GETDATE()) cantidad
,fcsh.PrecioUnitario PrecioUnitario
,fcsh.PrecioUnitario  AS  SubTotal
,0 AS  Impuesto
,DATEDIFF(DAY , FechaOcupacion,GETDATE()) * fcsh.PrecioUnitario total
FROM dbo.Servicios
RIGHT OUTER JOIN dbo.AtencionesEstanciaHospitalaria		ON dbo.Servicios.IdServicio = dbo.AtencionesEstanciaHospitalaria.IdServicio
LEFT OUTER JOIN dbo.Atenciones							ON dbo.AtencionesEstanciaHospitalaria.IdAtencion = dbo.Atenciones.IdAtencion
LEFT JOIN FactCatalogoServicios fcs						ON Servicios.IdProducto = fcs.IdProducto
LEFT JOIN FactPartidasPresupuestales fpp				ON fcs.IdPartida = fpp.IdPartidaPresupuestal
left JOIN FactCatalogoServiciosHosp fcsh ON fcs.IdProducto = fcsh.IdProducto AND IdFormaPago= fcsh.IdTipoFinanciamiento
WHERE dbo.Atenciones.IdCuentaAtencion = @cuenta AND Atenciones.IdTipoServicio=3
)
 
SELECT * FROM liquidacion
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_FARMACIA_ALMACENES]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[SIGESA_FARMACIA_ALMACENES]
as select * from farmAlmacen WHERE idTipoLocales = 'A' order by descripcion
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_FARMACIA_INGRESOS_ALMACEN]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_FARMACIA_INGRESOS_ALMACEN]
(
	@fechainicio date,
	@fechafin date,
	@idProveedor int
)
AS
IF(@idProveedor = 0)
	BEGIN
		SELECT	CONVERT(CHAR(10), m.fechaCreacion, 103) AS FECHA,
				mni.OrigenNumero [ORDEN DE COMPRA],
				p.RazonSocial [LABORATORIO], 
				'' [NRO DE ENTREGA],
				tp.descripcion LICITACION, 
				fcbi.Codigo [CODIGO SISMED],
				fcbi.Nombre [DESCRIPCIÓN DE PRODUCTO],
				md.Cantidad [CANTIDAD],
				m.Observaciones [OBSERVACIONES]
		FROM	farmMovimiento m 
		LEFT JOIN	farmMovimientoNotaIngreso mni	ON m.MovNumero = mni.MovNumero AND m.MovTipo = mni.MovTipo
		INNER JOIN	farmMovimientoDetalle md		ON m.MovNumero = md.MovNumero AND m.MovTipo = md.MovTipo
		LEFT JOIN	farmAlmacen a					ON m.idAlmacenOrigen = a.idAlmacen
		LEFT JOIN	farmAlmacen a1					ON m.idAlmacenDestino = a1.idAlmacen
		LEFT JOIN	Proveedores p					ON mni.idProveedor = p.idProveedor
		LEFT JOIN	farmTipoProceso tp				ON mni.idTipoProceso = tp.idTipoProceso
		LEFT JOIN	FactCatalogoBienesInsumos fcbi	ON md.idProducto = fcbi.IdProducto
		LEFT JOIN	farmEstadosMovimientos em		ON m.idEstadoMovimiento = em.idEstadoMovimiento

		 WHERE 1=1 AND m.idAlmacenDestino not IN (0) AND m.MovTipo = 'e'
		 AND m.fechaCreacion BETWEEN @fechainicio and @fechafin
	END
ELSE
BEGIN
	SELECT	CONVERT(CHAR(10), m.fechaCreacion, 103) AS FECHA,
			mni.OrigenNumero [ORDEN DE COMPRA],
			p.RazonSocial [LABORATORIO], 
			'' [NRO DE ENTREGA],
			tp.descripcion LICITACION, 
			fcbi.Codigo [CODIGO SISMED],
			fcbi.Nombre [DESCRIPCIÓN DE PRODUCTO],
			md.Cantidad [CANTIDAD],
			m.Observaciones [OBSERVACIONES]
	FROM	farmMovimiento m 
	LEFT JOIN	farmMovimientoNotaIngreso mni	ON m.MovNumero = mni.MovNumero AND m.MovTipo = mni.MovTipo
	INNER JOIN	farmMovimientoDetalle md		ON m.MovNumero = md.MovNumero AND m.MovTipo = md.MovTipo
	LEFT JOIN	farmAlmacen a					ON m.idAlmacenOrigen = a.idAlmacen
	LEFT JOIN	farmAlmacen a1					ON m.idAlmacenDestino = a1.idAlmacen
	LEFT JOIN	Proveedores p					ON mni.idProveedor = p.idProveedor
	LEFT JOIN	farmTipoProceso tp				ON mni.idTipoProceso = tp.idTipoProceso
	LEFT JOIN	FactCatalogoBienesInsumos fcbi	ON md.idProducto = fcbi.IdProducto
	LEFT JOIN	farmEstadosMovimientos em		ON m.idEstadoMovimiento = em.idEstadoMovimiento

	 WHERE 1=1 AND m.idAlmacenDestino not IN (0) AND m.MovTipo = 'e'
	 AND p.idProveedor = @idProveedor
	 AND m.fechaCreacion BETWEEN @fechainicio and @fechafin
END
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_FARMACIA_REPORTES_ALMACEN_TRASLADO]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--SUGERIDO DE EXCELL DE REPORTE DE TRASLADOS ENTRE UNIDADES EJECUTORAS PROGRAMA DE ALMACEN
--FECHA|NRO DE REGISTRO|ORIGEN|DESTINO|NRO DOCUMENTO|ESTADO|SISMED|DESCRIPCION|LOTE|F.V.|R.S.
CREATE PROCEDURE [dbo].[SIGESA_FARMACIA_REPORTES_ALMACEN_TRASLADO]
(
	@fechainicio date,
	@fechafin date,
	@idAlmacen int
)
AS
IF(@idAlmacen = 0)
	BEGIN
SELECT	CONVERT(CHAR(10), m.fechaCreacion, 103) FECHA,
		m.MovNumero as 'NRO REGISTRO',
		a.descripcion ORIGEN,
		a1.descripcion DESTINO,
		m.DocumentoNumero as 'NRO DOCUMENTO', 
		em.Estado ESTADO,
		fcbi.Codigo SISMED, 
		fcbi.Nombre DESCRIPCION , 
		md.Cantidad as 'CANTIDAD',
		--md.FechaVencimiento,
		md.Lote LOTE,
		--md.RegistroSanitario,
		CONVERT(CHAR(10), md.FechaVencimiento, 103) AS 'FV', 
		md.RegistroSanitario AS 'RS' 
FROM farmMovimiento m 
INNER JOIN	farmMovimientoDetalle md		ON m.MovNumero = md.MovNumero AND m.MovTipo = md.MovTipo
LEFT JOIN	farmAlmacen a					ON m.idAlmacenOrigen = a.idAlmacen
LEFT JOIN	farmAlmacen a1					ON m.idAlmacenDestino = a1.idAlmacen
LEFT JOIN	FactCatalogoBienesInsumos fcbi	ON md.idProducto = fcbi.IdProducto
LEFT JOIN	farmEstadosMovimientos em		ON m.idEstadoMovimiento = em.idEstadoMovimiento
 WHERE 1=1 
AND m.fechaCreacion BETWEEN @fechainicio and @fechafin
AND m.idAlmacenDestino not IN (0)
END
ELSE
BEGIN
SELECT	CONVERT(CHAR(10), m.fechaCreacion, 103) FECHA,
		m.MovNumero as 'NRO REGISTRO',
		a.descripcion ORIGEN,
		a1.descripcion DESTINO,
		m.DocumentoNumero as 'NRO DOCUMENTO', 
		em.Estado ESTADO,
		fcbi.Codigo SISMED, 
		fcbi.Nombre DESCRIPCION , 
		md.Cantidad as 'CANTIDAD',
		--md.FechaVencimiento,
		md.Lote LOTE,
		--md.RegistroSanitario,
		CONVERT(CHAR(10), md.FechaVencimiento, 103) AS 'FV', 
		md.RegistroSanitario AS 'RS' 
FROM farmMovimiento m 
INNER JOIN	farmMovimientoDetalle md		ON m.MovNumero = md.MovNumero AND m.MovTipo = md.MovTipo
LEFT JOIN	farmAlmacen a					ON m.idAlmacenOrigen = a.idAlmacen
LEFT JOIN	farmAlmacen a1					ON m.idAlmacenDestino = a1.idAlmacen
LEFT JOIN	FactCatalogoBienesInsumos fcbi	ON md.idProducto = fcbi.IdProducto
LEFT JOIN	farmEstadosMovimientos em		ON m.idEstadoMovimiento = em.idEstadoMovimiento
 WHERE 1=1 
AND m.fechaCreacion BETWEEN @fechainicio and @fechafin
 AND (m.idAlmacenDestino = @idAlmacen OR m.idAlmacenOrigen = @idAlmacen)
END
 
-- SIGESA_FARMACIA_REPORTES_ALMACEN_TRASLADO '2018-07-01','2018-07-31',2
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_LISTAR_PROVEEDORES]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_LISTAR_PROVEEDORES]
AS
select	idProveedor as 'IDPROVEEDOR',
		Ruc AS 'RUC',
		UPPER(RazonSocial) AS 'RAZONSOCIAL',
		UPPER(Direccion) AS 'DIRECCION'
from Proveedores
order by RazonSocial
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_MEDICAMENTOS_SERVICIOS_FACTURACION_CAJA]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--ec SIGESA_MEDICAMENTOS_SERVICIOS_FACTURACION_CAJA 2,'amox'

CREATE PROCEDURE [dbo].[SIGESA_MEDICAMENTOS_SERVICIOS_FACTURACION_CAJA]
(
	@TipoBusqueda INT,
	@lcFiltro varchar(MAX)
)
AS
DECLARE @SQL AS VARCHAR(MAX)

--------------------------------------DESCRIPCION SERVICIO
IF @TipoBusqueda = 0 BEGIN 
   SET  @SQL =  '
	SELECT
	DISTINCT
		FactCatalogoServicios.IdProducto
		,FactCatalogoServicios.Codigo
		,fpp.Codigo codPartida
		,FactCatalogoServicios.Nombre
		,'''' tipo
		,'''' saldo
		,FactCatalogoServiciosHosp.PrecioUnitario Precio
		,'''' idTipoSalidaBienInsumo
		,CASE
			WHEN FactCatalogoServicios.Codigo LIKE ''PRT%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18
			--WHEN FactCatalogoServicios.Codigo LIKE ''AD%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18
			ELSE FactCatalogoServiciosHosp.PrecioUnitario
		END PrecioUnitario
		,CASE
			WHEN FactCatalogoServicios.Codigo LIKE ''PRT%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18 * 0.18
			--WHEN FactCatalogoServicios.Codigo LIKE ''AD%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18 * 0.18
			ELSE 0
		END IGV
		,FactCatalogoServiciosHosp.idTipoFinanciamiento
		,tf.Descripcion financiamiento
		,'''' idTipoSalidaBienInsumoSaldo
	FROM (FactCatalogoServicios
	LEFT JOIN FactCatalogoServiciosHosp		ON FactCatalogoServicios.IdProducto = FactCatalogoServiciosHosp.IdProducto)
	LEFT JOIN FactPuntosCargaServicio		ON FactCatalogoServicios.IdServicioSubGrupo = FactPuntosCargaServicio.IdServicioSubGrupo
	INNER JOIN TiposFinanciamiento tf		ON FactCatalogoServiciosHosp.idTipoFinanciamiento = tf.idTipoFinanciamiento
	LEFT JOIN FactPartidasPresupuestales fpp		ON FactCatalogoServicios.IdPartida = fpp.IdPartidaPresupuestal
	WHERE FactCatalogoServicios.idEstado = 1	AND FactCatalogoServiciosHosp.Activo = 1 '+@lcFiltro
EXECUTE (@SQL)
END 


 
--------------------------------------CODIGO SERVICIO
IF @TipoBusqueda = 1 BEGIN  --, FactCatalogoServiciosHosp.Activo,FactCatalogoServiciosHosp.SeUsaSinPrecio 
set @sql='
SELECT DISTINCT
	FactCatalogoServicios.IdProducto
	,FactCatalogoServicios.Codigo
	,fpp.Codigo codPartida
	,FactCatalogoServicios.Nombre
	,'''' tipo
	,'''' saldo
	,FactCatalogoServiciosHosp.PrecioUnitario Precio
	,'''' idTipoSalidaBienInsumo
	,CASE
		WHEN FactCatalogoServicios.Codigo LIKE ''PRT%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18
		--WHEN FactCatalogoServicios.Codigo LIKE ''AD%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18
		ELSE FactCatalogoServiciosHosp.PrecioUnitario	END PrecioUnitario
	,CASE
		WHEN FactCatalogoServicios.Codigo LIKE ''PRT%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18 * 0.18
		--WHEN FactCatalogoServicios.Codigo LIKE ''AD%'' THEN FactCatalogoServiciosHosp.PrecioUnitario / 1.18 * 0.18
		ELSE 0	END IGV
	,FactCatalogoServiciosHosp.idTipoFinanciamiento
	,tf.Descripcion financiamiento
	,'''' idTipoSalidaBienInsumoSaldo
FROM (FactCatalogoServicios
LEFT JOIN FactCatalogoServiciosHosp	ON FactCatalogoServicios.IdProducto = FactCatalogoServiciosHosp.IdProducto)
LEFT JOIN FactPuntosCargaServicio	ON FactCatalogoServicios.IdServicioSubGrupo = FactPuntosCargaServicio.IdServicioSubGrupo
INNER JOIN TiposFinanciamiento tf	ON FactCatalogoServiciosHosp.idTipoFinanciamiento = tf.idTipoFinanciamiento
LEFT JOIN FactPartidasPresupuestales fpp	ON FactCatalogoServicios.IdPartida = fpp.IdPartidaPresupuestal
WHERE FactCatalogoServiciosHosp.Activo = 1 AND idEstado = 1 '+@lcFiltro
EXECUTE (@SQL)
END 

--------------------------------------DESCRIPCION FARMACIA
/*IF @TipoBusqueda = 2 BEGIN 

SET @lcFiltro=@lcFiltro+'%'

SELECT DISTINCT
	dbo.FarmSaldo.IdProducto
	,dbo.FactCatalogoBienesInsumos.Codigo
	,fpp.Codigo codPartida
	,dbo.FactCatalogoBienesInsumos.Nombre
	,dbo.farmTipoSalidaBienInsumo.tipo
	,dbo.FarmSaldo.Cantidad AS saldo
	,dbo.FarmSaldo.Precio
	,dbo.FactCatalogoBienesInsumos.IdTipoSalidaBienInsumo
	,dbo.FactCatalogoBienesInsumosHosp.PrecioUnitario/1.18  PrecioUnitario 
		,dbo.FactCatalogoBienesInsumosHosp.PrecioUnitario/1.18 *0.18 IGV -- nuevo
	,tf.idTipoFinanciamiento
	,tf.Descripcion financiamiento
	,dbo.FarmSaldo.IdTipoSalidaBienInsumo AS idTipoSalidaBienInsumoSaldo
FROM dbo.farmTipoSalidaBienInsumo
RIGHT OUTER JOIN dbo.FarmSaldo
	ON dbo.farmTipoSalidaBienInsumo.IdTipoSalidaBienInsumo = dbo.FarmSaldo.IdTipoSalidaBienInsumo
LEFT OUTER JOIN dbo.FactCatalogoBienesInsumosHosp
LEFT OUTER JOIN dbo.FactCatalogoBienesInsumos
	ON dbo.FactCatalogoBienesInsumosHosp.IdProducto = dbo.FactCatalogoBienesInsumos.IdProducto
	ON dbo.FarmSaldo.IdProducto = dbo.FactCatalogoBienesInsumos.IdProducto
INNER JOIN TiposFinanciamiento tf
	ON FactCatalogoBienesInsumosHosp.idTipoFinanciamiento = tf.idTipoFinanciamiento
LEFT JOIN FactPartidasPresupuestales fpp
	ON FactCatalogoBienesInsumos.IdPartida = fpp.IdPartidaPresupuestal
WHERE (dbo.FactCatalogoBienesInsumosHosp.idTipoFinanciamiento = 1)
AND Petitorio = 1
AND  --dbo.farmSaldo.IdAlmacen = @IdAlmacen and   
dbo.FarmSaldo.Cantidad > 0
AND FarmSaldo.IdAlmacen = 6
AND dbo.FactCatalogoBienesInsumos.Nombre LIKE @lcFiltro
ORDER BY dbo.FactCatalogoBienesInsumos.Nombre


END
--------------------------------------CODIGO FARMACIA
IF @TipoBusqueda = 3 BEGIN 
SELECT DISTINCT
	dbo.FarmSaldo.IdProducto
	,dbo.FactCatalogoBienesInsumos.Codigo
	,fpp.Codigo codPartida
	,dbo.FactCatalogoBienesInsumos.Nombre
	,dbo.farmTipoSalidaBienInsumo.tipo
	,dbo.FarmSaldo.Cantidad AS saldo
	,dbo.FarmSaldo.Precio
	,dbo.FactCatalogoBienesInsumos.IdTipoSalidaBienInsumo
	,dbo.FactCatalogoBienesInsumosHosp.PrecioUnitario/1.18  PrecioUnitario 
	,dbo.FactCatalogoBienesInsumosHosp.PrecioUnitario/1.18 *0.18 IGV  -- nuevo
	,tf.idTipoFinanciamiento
	,tf.Descripcion financiamiento
	,dbo.FarmSaldo.IdTipoSalidaBienInsumo AS idTipoSalidaBienInsumoSaldo
FROM dbo.farmTipoSalidaBienInsumo
RIGHT OUTER JOIN dbo.FarmSaldo
	ON dbo.farmTipoSalidaBienInsumo.IdTipoSalidaBienInsumo = dbo.FarmSaldo.IdTipoSalidaBienInsumo
LEFT OUTER JOIN dbo.FactCatalogoBienesInsumosHosp
LEFT OUTER JOIN dbo.FactCatalogoBienesInsumos
	ON dbo.FactCatalogoBienesInsumosHosp.IdProducto = dbo.FactCatalogoBienesInsumos.IdProducto
	ON dbo.FarmSaldo.IdProducto = dbo.FactCatalogoBienesInsumos.IdProducto
INNER JOIN TiposFinanciamiento tf
	ON FactCatalogoBienesInsumosHosp.idTipoFinanciamiento = tf.idTipoFinanciamiento
LEFT JOIN FactPartidasPresupuestales fpp
	ON FactCatalogoBienesInsumos.IdPartida = fpp.IdPartidaPresupuestal
WHERE dbo.FactCatalogoBienesInsumosHosp.idTipoFinanciamiento = 1
AND Petitorio = 1
AND dbo.FactCatalogoBienesInsumos.Codigo = @lcFiltro
AND dbo.FarmSaldo.Cantidad > 0
AND FarmSaldo.IdAlmacen = 6
ORDER BY dbo.FactCatalogoBienesInsumos.Codigo
END */
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_RolesItemsSeleccionarGruposPorUsuario]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_RolesItemsSeleccionarGruposPorUsuario]
(
@lIdUsuario int
)
AS

select distinct ListBarGrupos.IdListGrupo, ListBarGrupos.Clave
                 , ListBarGrupos.Texto , ListBarGrupos.Indice, ListBarGrupos.IconosWeb
             From
                 (((Empleados
                 left join UsuariosRoles on Empleados.IdEmpleado = UsuariosRoles.IdEmpleado)
                 left join RolesItems on UsuariosRoles.IdRol = RolesItems.IdRol)
                 left join ListBarItems on RolesItems.IdListItem = ListBarItems.IdListItem)
                 left join ListBarGrupos on ListBarGrupos.IdListGrupo = ListBarItems.IdListGrupo
             where Empleados.IdEmpleado = @lIdUsuario 
             order by ListBarGrupos.Indice asc
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_Tipo_Seguro_x_DNI]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_Tipo_Seguro_x_DNI]
(
	@dni VARCHAR(12) 
)
as

SELECT DISTINCT 
dbo.FacturacionCuentasAtencion.IdCuentaAtencion, dbo.EstadosCuenta.Descripcion AS EstadoCuenta, dbo.Atenciones.IdPaciente,
dbo.Atenciones.IdAtencion, CONVERT(char(10), dbo.Atenciones.FechaIngreso, 103) AS FechaIngreso, dbo.Atenciones.HoraIngreso,
dbo.Pacientes.NroHistoriaClinica, dbo.Pacientes.ApellidoPaterno, dbo.Pacientes.ApellidoMaterno, dbo.Pacientes.PrimerNombre,
dbo.Pacientes.SegundoNombre, CONVERT(char(10), dbo.Atenciones.FechaEgreso, 103) AS FechaEgreso, dbo.Atenciones.HoraEgreso,
dbo.FacturacionCuentasAtencion.IdEstado, dbo.Servicios.Nombre AS ServicioIngreso, dbo.TiposServicio.Descripcion AS dTipoServicio, dbo.Atenciones.Edad AS Edad, dbo.Pacientes.IdTipoNumeracion,
dbo.Atenciones.IdTipoServicio,Atenciones.idFuenteFinanciamiento,ff.Descripcion Financiamiento, dbo.Atenciones.IdServicioIngreso
FROM dbo.Atenciones 
LEFT OUTER JOIN dbo.Servicios ON dbo.Atenciones.IdServicioIngreso = dbo.Servicios.IdServicio 
LEFT OUTER JOIN dbo.Pacientes ON dbo.Atenciones.IdPaciente = dbo.Pacientes.IdPaciente 
LEFT OUTER JOIN dbo.FacturacionCuentasAtencion ON dbo.Atenciones.IdCuentaAtencion = dbo.FacturacionCuentasAtencion.IdCuentaAtencion 
LEFT OUTER JOIN dbo.EstadosCuenta ON dbo.EstadosCuenta.IdEstado = dbo.FacturacionCuentasAtencion.IdEstado 
LEFT OUTER JOIN dbo.TiposServicio ON dbo.TiposServicio.IdTipoServicio = dbo.Atenciones.IdTipoServicio 
LEFT OUTER JOIN FuentesFinanciamiento ff ON Atenciones.idFuenteFinanciamiento = ff.IdFuenteFinanciamiento
where   idEstadoAtencion=1  and  Pacientes.NroDocumento = @dni
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_CABECERA_NOTA_X_CODIGO]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_CABECERA_NOTA_X_CODIGO]
@idcomprobante int
AS
BEGIN
select * from sunatNota_Cabecera WHERE IdComprobantePago=@idcomprobante
END
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_CABECERA_X_CODIGO]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_CABECERA_X_CODIGO]
@idcomprobante int
AS
BEGIN
select * from SUNAT_CABECERA WHERE IDCOMPROBANTE=@idcomprobante
END
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_DETALLE_X_CODIGO]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_DETALLE_X_CODIGO]
@IdComprobantePago int
as
SELECT
	IDORDEN,
	IDPRODUCTO,
	NOMBRE,
	CANTIDADPAGAR,
	VALORUNITARIO,
	VALORTOTALSINIGV,
	TOTALIMPUESTO,
	PRECIOVENTA,
	TOTALPAGAR
FROM SUNAT_DETALLE WHERE IDCOMPROBANTE=@IdComprobantePago
/* RODO
SELECT	FacturacionBienesPagos.IdOrden,		
		FacturacionBienesPagos.IdProducto,
		factCatalogoBienesInsumos.Nombre,
		FacturacionBienesPagos.CantidadPagar,
		(FacturacionBienesPagos.PrecioVenta*0.82) AS VALORUNITARIO,
		(FacturacionBienesPagos.PrecioVenta*0.82)*CAST(FacturacionBienesPagos.CantidadPagar AS INT) AS VALORTOTALSINIGV,
		(FacturacionBienesPagos.TotalPagar - ((FacturacionBienesPagos.PrecioVenta*0.82)*CAST(FacturacionBienesPagos.CantidadPagar AS INT))) AS TOTALIMPUESTO,
		FacturacionBienesPagos.PrecioVenta,
		FacturacionBienesPagos.TotalPagar

FROM	FacturacionBienesPagos
INNER	JOIN	FactCatalogoBienesInsumos ON FactCatalogoBienesInsumos.IdProducto=FacturacionBienesPagos.IdProducto
INNER	JOIN	FactOrdenesBienes ON FactOrdenesBienes.idOrden = FacturacionBienesPagos.IdOrden
WHERE FactOrdenesBienes.idComprobantePago = @IdComprobantePago
ORDER BY FacturacionBienesPagos.IdOrden
*/
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_ICI_FORMATLM]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_ICI_FORMATLM]
@fechainicio datetime,
@fechafin datetime
as
SELECT distinct
	(select '0'+ValorTexto from Parametros where IdParametro=239) AS [Codigo_eje],
	(select ValorTexto+'F01' from Parametros where IdParametro=208) AS [Codigo_pre], --SOLO PARA TODAS LAS FARMACIAS
	CASE 
		WHEN MONTH(@fechainicio)<10 THEN CAST(YEAR(@fechainicio) as varchar)+'0'+CAST(MONTH(@fechainicio) as varchar)
		ELSE CAST(YEAR(@fechainicio) as varchar)+CAST(MONTH(@fechainicio) as varchar)--+CAST(DATEPART(month, @fechafin) as varchar) as [Annomes],
	END as [Annomes],
	'S' AS [Tipsum], --TODAS LAS FARMACIAS SIEMPRES S // S=SIN DONACIONES D=DONACIONES
	FCBI.Codigo,
	FCBI.IdProducto,
	(SELECT top 1 convert(varchar(10),dbo.farmSaldoDetallado.FechaVencimiento,101)
		FROM         dbo.farmSaldoDetallado LEFT OUTER JOIN
		             dbo.farmTipoSalidaBienInsumo ON 
		             dbo.farmSaldoDetallado.idTipoSalidaBienInsumo = dbo.farmTipoSalidaBienInsumo.idTipoSalidaBienInsumo LEFT OUTER JOIN
						dbo.FactCatalogoBienesInsumos ON dbo.farmSaldoDetallado.idProducto = dbo.FactCatalogoBienesInsumos.IdProducto
		where dbo.farmSaldoDetallado.IdAlmacen in (102,114,8,3,6,5,4,111) and dbo.FactCatalogoBienesInsumos.Codigo=FCBI.Codigo and dbo.farmSaldoDetallado.Cantidad>0
		order by  dbo.farmSaldoDetallado.FechaVencimiento) as fec_venc,
		(SELECT top 1 dbo.farmSaldoDetallado.Lote
		FROM         dbo.farmSaldoDetallado LEFT OUTER JOIN
		             dbo.farmTipoSalidaBienInsumo ON 
		             dbo.farmSaldoDetallado.idTipoSalidaBienInsumo = dbo.farmTipoSalidaBienInsumo.idTipoSalidaBienInsumo LEFT OUTER JOIN
						dbo.FactCatalogoBienesInsumos ON dbo.farmSaldoDetallado.idProducto = dbo.FactCatalogoBienesInsumos.IdProducto
		where dbo.farmSaldoDetallado.IdAlmacen in (102,114,8,3,6,5,4,111) and dbo.FactCatalogoBienesInsumos.Codigo=FCBI.Codigo and dbo.farmSaldoDetallado.Cantidad>0
		order by  dbo.farmSaldoDetallado.FechaVencimiento) as Lote,
		'1' AS Sit
FROM         dbo.farmMovimientoDetalle
	LEFT OUTER JOIN dbo.farmMovimiento ON dbo.farmMovimientoDetalle.MovNumero = dbo.farmMovimiento.MovNumero AND dbo.farmMovimientoDetalle.MovTipo = dbo.farmMovimiento.MovTipo 
	LEFT OUTER JOIN dbo.FactCatalogoBienesInsumos FCBI ON dbo.farmMovimientoDetalle.idProducto = FCBI.IdProducto
	WHERE     (dbo.farmMovimiento.idEstadoMovimiento = 1)     and (dbo.farmMovimiento.fechaCreacion Between  @fechainicio and @fechafin)
	ORDER BY FCBI.IdProducto, FCBI.Codigo
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_ICI_FORMATO]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_ICI_FORMATO]
@fechainicio datetime,
@fechafin datetime
as
select * from(
SELECT
	(select '0'+ValorTexto from Parametros where IdParametro=239) AS [Codigo_eje],
	(select ValorTexto+'F01' from Parametros where IdParametro=208) AS [Codigo_pre], --SOLO PARA TODAS LAS FARMACIAS
	CASE 
		WHEN MONTH(@fechainicio)<10 THEN CAST(YEAR(@fechainicio) as varchar)+'0'+CAST(MONTH(@fechainicio) as varchar)
		ELSE CAST(YEAR(@fechainicio) as varchar)+CAST(MONTH(@fechainicio) as varchar)--+CAST(DATEPART(month, @fechafin) as varchar) as [Annomes],
	END as [Annomes],
	'S' AS [Tipsum], --TODAS LAS FARMACIAS SIEMPRES S // S=SIN DONACIONES D=DONACIONES
	'F' AS [Tipo_pre], --CONSTANTE
	CASE 
	WHEN idTipoFinanciamiento=1 and idFuenteFinanciamiento!=5 THEN 'Rec_vtas'
	WHEN idTipoFinanciamiento=1 and idFuenteFinanciamiento=5 THEN 'Rec_crehos'
	WHEN idTipoFinanciamiento=2 THEN 'Rec_sis'
	WHEN idTipoFinanciamiento=3 THEN 'Rec_soat'
	WHEN idTipoFinanciamiento=9 THEN 'Rec_exo'
	ELSE 'Rec_otrcon'
	END AS [TIPO],
	(SELECT       
		COUNT(*)
		From farmMovimiento
		WHERE  (MovTipo = 'S') AND (idTipoConcepto = 16) and 
		(idEstadoMovimiento=1) and 
		(fechaCreacion Between @fechainicio and @fechafin))
	AS[Rec_ints],
	'A' AS [Indiproc],
	CONVERT(char,SYSDATETIME(),103) AS [Fecha],
	'  :  :  ' as [Fechault],
	'V2.0 04102011' as [Vers],
	'1' as [Sit],
	CONVERT(char,@fechainicio,103) as [Fdesde],
	CONVERT(char,@fechafin,103) as [Fhasta],
	'P' as [Ctrlcal],
	CONVERT(char,SYSDATETIME(),103) as [Catalogo],
	(select '000'+ValorTexto from Parametros where IdParametro=208) as [Codpto],
	'E' as [Tip_ins]

	FROM dbo.farmMovimiento 
	INNER JOIN dbo.farmMovimientoVentas 
	ON dbo.farmMovimiento.MovNumero = dbo.farmMovimientoVentas.movNumero AND
	dbo.farmMovimiento.MovTipo = dbo.farmMovimientoVentas.MovTipo
	WHERE dbo.farmMovimiento.idEstadoMovimiento=1 and
	(dbo.farmMovimiento.fechaCreacion Between @fechainicio
	and @fechafin)
)src
PIVOT(
	COUNT([TIPO]) FOR [TIPO] IN ([Rec_vtas],[Rec_sis],[Rec_dn],[Rec_exo],[Rec_soat],[Rec_crehos],[Rec_otrcon])
)q
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_ICI_FORMDET]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_ICI_FORMDET]
@fechainicio datetime,
@fechafin datetime
as
SELECT * FROM(	
	SELECT 
	(select '0'+ValorTexto from Parametros where IdParametro=239) AS [Codigo_eje],
	(select ValorTexto+'F01' from Parametros where IdParametro=208) AS [Codigo_pre], --SOLO PARA TODAS LAS FARMACIAS
	'S' AS [Tipsum],
	CASE 
		WHEN MONTH(@fechainicio)<10 THEN CAST(YEAR(@fechainicio) as varchar)+'0'+CAST(MONTH(@fechainicio) as varchar)
		ELSE CAST(YEAR(@fechainicio) as varchar)+CAST(MONTH(@fechainicio) as varchar)--+CAST(DATEPART(month, '2018-06-30 23:59:59') as varchar) as [Annomes],
	END as [Annomes],
	 
	FCBI.Codigo AS codigo_med,
	'0'		as 'saldo',
	--FCBI.Nombre,--
	--FCBI.IdProducto,
	(SELECT TOP 1 Precio FROM farmMovimientoDetalle WHERE MovTipo='E' AND idProducto=FCBI.IdProducto order by MovNumero desc) AS precio, 
	FMD.Cantidad,
	CASE FMD.MovTipo
	WHEN 'E'
		THEN(
			CASE FM.idTipoConcepto	
		    WHEN '1' THEN 'ingre'
			WHEN '4' THEN 'ingre'--'DISTRIBUCION' 
			WHEN '7' THEN 'ingre'
			WHEN '9' THEN 'ingre' -- transferencia otras unidades ejecutoras
			WHEN '30' THEN 'ingre'
			WHEN '28' THEN 'ingre'
			ELSE 'reingre'
			/*WHEN '1' THEN 'ENTRADA COMPRA'
			WHEN '21' THEN 'ENTRADA DEVOLUCION'
			WHEN '4' THEN 'ENTRADA DISTRIBUCION'
			WHEN '7' THEN 'ENTRADA DEVOL. SOBRESTOCK'
			WHEN '5' THEN 'ENTRADA CANJE POR VENCIMIENTO'
			WHEN '27' THEN 'ENTRADA DONACION A PACIENTES'
			WHEN '30' THEN 'ENTRADA PRESTAMOS'
			WHEN '34' THEN 'ENTRADA CANJE DE LABORATORIO'
			WHEN '35' THEN 'ENTRADA CARGO GUIA'*/
		END		
		)
	WHEN 'S'
		THEN(
			CASE FM.idTipoConcepto	
			WHEN '10' THEN 'ventas'
			--WHEN '32' THEN 'ventas'
			WHEN '13' THEN 'sis'
			WHEN '16' THEN 'intersan'
			WHEN '15' THEN 'exo'
			WHEN '14' THEN 'soat'
			WHEN '17' THEN 'credhosp'
			WHEN '23' THEN 'otr_conv'
			WHEN '22' THEN 'DEF. NAC'
			WHEN '7' THEN 'devol'
			WHEN '21' THEN 'devol'
			WHEN '28' THEN 'devol'
			WHEN '31' THEN 'devol'
			WHEN '6' THEN 'merma'
			WHEN '5' THEN 'vencido'
			WHEN '9' THEN 'transf'
			ELSE 'otros_sal'
		END		
		)END AS TIPO,
		(SELECT top 1 convert(varchar(10), dbo.farmSaldoDetallado.FechaVencimiento,101)
		FROM         dbo.farmSaldoDetallado LEFT OUTER JOIN
		             dbo.farmTipoSalidaBienInsumo ON 
		             dbo.farmSaldoDetallado.idTipoSalidaBienInsumo = dbo.farmTipoSalidaBienInsumo.idTipoSalidaBienInsumo LEFT OUTER JOIN
						dbo.FactCatalogoBienesInsumos ON dbo.farmSaldoDetallado.idProducto = dbo.FactCatalogoBienesInsumos.IdProducto
		where dbo.farmSaldoDetallado.IdAlmacen in (102,114,8,3,6,5,4,111) and dbo.FactCatalogoBienesInsumos.Codigo=FCBI.Codigo and dbo.farmSaldoDetallado.Cantidad>0
		order by  dbo.farmSaldoDetallado.FechaVencimiento) as fec_exp
	FROM farmMovimientoDetalle FMD
	INNER JOIN FarmMovimiento FM ON FM.MovNumero=FMD.MovNumero AND FM.MovTipo=FMD.MovTipo
	FULL JOIN FactCatalogoBienesInsumos FCBI ON FCBI.IdProducto=FMD.idProducto 
	WHERE 
	FM.idEstadoMovimiento=1 and 
	FM.fechaCreacion BETWEEN (@fechainicio) AND (@fechafin) and
	FM.idAlmacenDestino not in ('112','116','115','107','9','109','108','113')
)SRC
PIVOT (
	 SUM(Cantidad) for TIPO in  ([ingre],[reingre],[ventas],[sis],[intersan],[fac_perd],[defnac],[exo],[soat],[credhosp],[otro_conv],[devol],[vencido],[merma],[distri],[transf],[ventainst],[dev_ven],[dev_merma],[otros_sal])
)q
ORDER BY codigo_med
--SELECT * FROM farmTipoConceptos
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_LISTA_IDCOMPROBANTES_XFECHA]    Script Date: 13/12/2018 3:23:11 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_LISTA_IDCOMPROBANTES_XFECHA]
@FECHAEMISION VARCHAR(8)
AS
BEGIN
	SELECT top 1 IDCOMPROBANTE FROM SUNAT_CABECERA WHERE FECHAEMISION=(SUBSTRING(@FECHAEMISION, 0, 5))+'-'+(SUBSTRING(@FECHAEMISION, 5, 2))+'-'+(SUBSTRING(@FECHAEMISION, 7, 2))
END
GO
