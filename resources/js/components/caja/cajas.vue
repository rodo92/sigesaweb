<template>    
    <div>
        <section class="content-header">
            <h1>
                Cajas
                <small>Caja</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="restaurar"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active">Caja</li>
            </ol>
        </section>

    <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
            <div class="box box-default color-palette-box text-center" id="">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <table style="width: 70%; text-align: left;">
                                <tr>
                                    <td width="20%" style="padding-right: 1%;">
                                        <label>Seleccione una Caja:</label>
                                    </td>
                                    <td width="30%" style="padding-right: 1%;">
                                        <select class="form-control">
                                            
                                        </select>
                                    </td>
                                    <td width="20%" style="padding-right: 1%;">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
                                                APERTURA
                                            </label>
                                        </div>  
                                    </td>
                                    <td width="20%">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
                                                CIERRE
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>