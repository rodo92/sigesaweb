USE [SIGH]
GO

ALTER TABLE [dbo].[ListBarGrupos] ADD IconosWeb varchar(100)
/****** Object:  StoredProcedure [dbo].[SIGESA_FARMACIA_ALMACENES]    Script Date: 11/15/2018 16:23:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[SIGESA_FARMACIA_ALMACENES]
as select * from farmAlmacen WHERE idTipoLocales = 'A' order by descripcion
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_RolesItemsSeleccionarGruposPorUsuario]    Script Date: 11/15/2018 16:23:45 ******/
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
/****** Object:  StoredProcedure [dbo].[SIGESA_FARMACIA_REPORTES_ALMACEN_TRASLADO]    Script Date: 11/15/2018 16:23:45 ******/
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
		md.Lote LOTE,
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
		md.Lote LOTE,
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
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_ICI_FORMATLM]    Script Date: 11/15/2018 16:23:45 ******/
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
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_ICI_FORMDET]    Script Date: 11/15/2018 16:23:45 ******/
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
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_ICI_FORMATO]    Script Date: 11/15/2018 16:23:45 ******/
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
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_CABECERA_X_CODIGO]    Script Date: 11/15/2018 16:23:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SIGESA_TRAMA_CABECERA_X_CODIGO]
@IdComprobantePago int
as
SELECT
----------------------------------IDE--------------------------------
CCP.IdComprobantePago as IDCOMPROBANTE,
CCP.NroSerie+'-'+CCP.NroDocumento AS numeracion,
CONVERT(CHAR(10), CCP.FechaCobranza,120) AS fechaEmision,
CONVERT(CHAR(8), CCP.FechaCobranza, 108) AS horaEmision, --OPCIONAL
CCP.IdTipoComprobante AS codTipoDocumento,
'PEN' AS tipoMoneda, --VALOR ESTABLE SIN DATOS EN TABLA (CajaTiposMoneda)
'' AS numeroOrdenCompra, --OPCIONAL
'' AS fechaVencimiento, -- OPCIONAL
--------------------------------EMI-----------------------------------
'6' AS tipoDocIdEMI,
(SELECT ValorTexto FROM Parametros WHERE Tipo='DATOS_GENERALES' AND Codigo='RUC_EESS') AS numeroDocIdEMI,
'' AS nombreComercial, --OPCIONAL,
(SELECT ValorTexto FROM Parametros WHERE Tipo='DATOS_GENERALES' AND Codigo='NOMBRE') AS razonSocialEMI,
(SELECT ValorTexto FROM Parametros WHERE Tipo='DATOS_GENERALES' AND Codigo='UBIGEO_HOSP') AS ubigeoEMI, --OPCIONAL
(SELECT ValorTexto FROM Parametros WHERE Tipo='DATOS_GENERALES' AND Codigo='DIRECCION') AS direccionEMI,
'' AS provincia,  --OPCIONAL
'' AS departamento, --OPCIONAL
'' AS distrito, --OPCIONAL
'' AS codigoPais, --OPCIONAL
'' AS telefono, --OPCIONAL
'' AS correoElectronico, --OPCIONAL
----------------------------------REC-------------------------------------------
(CASE P.IdDocIdentidad  
         WHEN 1 THEN 1
         WHEN 2 THEN 4
         WHEN 3 THEN 7 
         ELSE null
END) AS tipoDocRelacionado,
P.NroDocumento AS numeroDocId,
P.NroDocumento AS numeroDocRelacionado,
CCP.RazonSocial AS razonSocial,
P.DireccionDomicilio AS direccion, --OPCIONAL
'' AS departamento, --OPCIONAL
'' AS provincia, --OPCIONAL
'' AS distrito, --OPCIONAL
'' AS codigoPais, --OPCIONAL
'' AS telefono, --OPCIONAL
'' AS correoElectronico, --OPCIONAL

--------------------------------- CAB ---------------------------------------------
(CASE CCP.IdTipoComprobante
	WHEN 3 THEN 'GRAVADAS'
	WHEN 2 THEN 'GRAVADAS'
END) AS CAB,
(CASE CCP.IdTipoComprobante
	WHEN 3 THEN '1001'
	WHEN 2 THEN '1001'
END) AS CAB_CODIGO,
CCP.SubTotal AS CAB_TOTALVENTAS,
'1000' AS 'CODIGO_IMPUESTO',
CCP.IGV AS 'TOTAL_IMPUESTO',
CCP.Total AS 'IMPORTE_TOTAL'


FROM CajaComprobantesPago CCP
INNER JOIN Pacientes P ON CCP.IdPaciente=P.IdPaciente
INNER JOIN CajaTiposComprobante CTC ON CCP.IdTipoComprobante=CTC.IdTipoComprobante
WHERE CCP.IdComprobantePago = @IdComprobantePago
--WHERE CCP.IdComprobantePago IN ('584198','621121','626694','632436','584410','589820','624631','595140','647982','635622')

--exec SIGESA_TRAMA_CABECERA_X_CODIGO 584198
GO
/****** Object:  StoredProcedure [dbo].[SIGESA_TRAMA_DETALLE_X_CODIGO]    Script Date: 11/15/2018 16:23:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create PROCEDURE [dbo].[SIGESA_TRAMA_DETALLE_X_CODIGO]
@IdComprobantePago int
as
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
GO


-- PROCEDIMIENTO PARA LISTAR LOS PROVEEDORES
CREATE PROCEDURE [dbo].[SIGESA_LISTAR_PROVEEDORES]
AS
select	idProveedor as 'IDPROVEEDOR',
		Ruc AS 'RUC',
		UPPER(RazonSocial) AS 'RAZONSOCIAL'
from Proveedores
order by RazonSocial

-- PROCEDIMIENTO PARA MOSTRAR LOS INGRESO DE ALMACEN
-- FILTRADOS POR RANGO DE FECHA Y PROVEEDOR
CREATE PROCEDURE [dbo].[SIGESA_FARMACIA_INGRESOS_ALMACEN]
(
	@fechainicio date,
	@fechafin date,
	@idProveedor int
)
AS
IF(@idProveedor = 0)
	BEGIN
		SELECT	m.fechaCreacion FECHA,
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
	SELECT	m.fechaCreacion FECHA,
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