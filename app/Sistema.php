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
}
