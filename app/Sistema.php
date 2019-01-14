<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sistema extends Model
{
    public function validar_datos_login($usuario,$contrasenia)
    {
    	$result = DB::select('select Empleados.IdEmpleado,
							Empleados.ApellidoPaterno,
							Empleados.ApellidoMaterno,
							Empleados.Nombres,
							Empleados.DNI,
							Empleados.CodigoPlanilla,
							Empleados.FechaIngreso,
							Empleados.Usuario,
							Empleados.loginPC,
							YEAR(Empleados.FechaIngreso) AS AnioIngreso,
							UsuariosRoles.IdUsuarioRol,
							Roles.Nombre as Rol
							from Empleados  
							left join UsuariosRoles on UsuariosRoles.IdEmpleado=Empleados.IdEmpleado
							left join Roles on Roles.IdRol=UsuariosRoles.IdRol
							where Empleados.Usuario=? and Empleados.ClaveVWeb= ?',[$usuario,$contrasenia]);
    	return json_decode(json_encode($result), true);
    }

    public function EmpleadoPermisoMenu_M($IdEmpleado) {
        $result = DB::select('exec SIGESA_RolesItemsSeleccionarGruposPorUsuario ?',[$IdEmpleado]);                      
    	return json_decode(json_encode($result), true);
    }

    public function EmpleadoPermisoSubMenu_M($IdListGrupo, $IdEmpleado) {
        $result = DB::select('exec RolesItemsSeleccionarItemsPorUsuarioYGrupo ?,?',[$IdListGrupo,$IdEmpleado]);   
        return json_decode(json_encode($result), true);
    }

    public function Mostrar_Provedores()
    {
        $result = DB::select('exec SIGESA_LISTAR_PROVEEDORES');

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Tipo_Comprobante($IdTipoComprobante)
    {
        $result = DB::table('CajaTiposComprobante')
                ->where('IdTipoComprobante','=',$IdTipoComprobante)->get();

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Proveedor($ruc)
    {
        $result = DB::table('Proveedores')
                ->where('Proveedores.Ruc','=',$ruc)
                ->select('Proveedores.Direccion')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Proveedor_id($idProveedor)
    {
        $result = DB::table('Proveedores')
                ->where('Proveedores.idProveedor','=',$idProveedor)
                ->select('Proveedores.*')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Servicio_Por_Especialidad($idespecialidad)
    {
        $result = DB::table('Servicios')
                ->where('Servicios.IdEspecialidad','=',$idespecialidad)
                ->select('Servicios.IdServicio','Servicios.Nombre')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Especialidades()
    {
        $result = DB::table('Especialidades')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Tipo_Servicio()
    {
        $result = DB::table('TipoServicio')
                ->get();

        return json_decode(json_encode($result), true);
    }
    
    public function Obtener_Especialidades_Tipo_Servicio($idTipoServicio)
    {
        $result = DB::table('Servicios')
                ->leftJoin('Especialidades', 'Especialidades.IdEspecialidad', '=', 'Servicios.IdEspecialidad')
                ->select('Servicios.IdEspecialidad','Especialidades.Nombre')
                ->where('Servicios.IdTipoServicio','=',$idTipoServicio)
                ->distinct()
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Insertar_Proveedor($Ruc,$RazonSocial,$Direccion)
    {
        $datos = array(
            'Ruc'           => $Ruc,
            'RazonSocial'   => $RazonSocial,
            'Direccion'     => $Direccion
            );
        $idProveedor = DB::table('Proveedores')->insertGetId($datos);
        return json_decode(json_encode($idProveedor), true);
    }
}
