<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Farmacia;

class ReporteICIController extends Controller
{
    public function index()
    {
    	return view('farmacia.reporteici');
    }

    public function generar_dbf(Request $request)
    {
    	$messages = [
            'inicio.required'   => 'Debe ingresar una fecha de inicio',
            'fin.required'  	=> 'Debe ingresar una fecha de fin'
        ];

        $rules = [
            'inicio'    => 'required',
            'fin'   	=> 'required'
        ];

        $this->validate($request,$rules,$messages);

        $fechainicio = $request->inicio;
    	$fechafin    = $request->fin;

		/**
		 * ordenando fechas
		 */

		list($dia,$mes,$anio)=explode("/",$fechainicio);
		$fechainicio =  $anio."-".$mes."-".$dia . ' 00:00:00.000';

		list($dia,$mes,$anio)=explode("/",$fechafin);
		$fechafin =  $anio."-".$mes."-".$dia . ' 23:59:59.000';



		/**
		 * Formato ICI
		 */

		$data_codigo_saldos[] =  null;
		$pre_formato = false;
		$pre_formdet = false;
		$pre_formdetl = false;
        $pre_formdetm = false;

		$Farmacia = new Farmacia();
		$pre_formato  = $Farmacia->ICI_Formato($fechainicio,$fechafin);
		$pre_formdet  = $Farmacia->ICI_Formdet($fechainicio,$fechafin);
		$pre_formdetl = $Farmacia->ICI_Formdtlm($fechainicio,$fechafin);
        $pre_formdetm = $Farmacia->ICI_Formdtlm($fechainicio,$fechafin);

		/**
		 * Cabecera
		 */
		$cabecera_formato = array(
			array("codigo_eje", 	"C", 3),
			array("codigo_pre", 	"C", 11),
			array("annomes", 		"C", 6),
			array("tipsum", 		"C", 1),
			array("tipo_pre", 		"C", 1),
			array("rec_vtas", 		"N", 8, 0),
			array("rec_sis", 		"N", 8, 0),
			array("rec_ints", 		"N", 8, 0),
			array("rec_dn", 		"N", 8, 0),
			array("rec_exo", 		"N", 8, 0),
			array("rec_soat", 		"N", 8, 0),
			array("rec_crehos", 	"N", 8, 0),
			array("rec_otrcon", 	"N", 8, 0),
			array("indiproc", 		"C", 1),
			array("fecha", 			"D"),
			array("fechault", 		"D"),
			array("vers", 			"C", 13),
			array("sit", 			"C", 1),
			array("fdesde", 		"D"),
			array("fhasta", 		"D"),
			array("ctrlcal", 		"C", 1),
			array("catalogo", 		"D"),
			array("codpto", 		"C", 11),
			array("tip_ins", 		"C", 1)
		);

		$cabecera_formdet = array(
			array("codigo_eje", 	"C", 3),
			array("codigo_pre", 	"C", 11),
			array("tipsum", 		"C", 1),
			array("annomes", 		"C", 6),
			array("codigo_med",		"C", 7),
			array("saldo", 			"N", 18, 0),
			array("precio", 		"N", 10, 6), #tipo double
			array("ingre", 			"N", 18, 0),
			array("reingre", 		"N", 18, 0),
			array("venta", 			"N", 8, 0),
			array("sis", 			"N", 8, 0),
			array("intersan", 		"N", 8, 0),
			array("fac_perd", 		"N", 8, 0),
			array("defnac", 		"N", 8, 0),
			array("exo", 			"N", 8, 0),
			array("soat", 			"N", 8, 0),
			array("credhosp", 		"N", 8, 0),
			array("otr_conv", 		"N", 8, 0),
			array("devol", 			"N", 8, 0),
			array("vencido", 		"N", 8, 0),
			array("merma", 			"N", 8, 0),
			array("distri", 		"N", 8, 0),
			array("transf", 		"N", 8, 0),
			array("ventainst", 		"N", 8, 0),
			array("dev_ven", 		"N", 8, 0),
			array("dev_merma", 		"N", 8, 0),
			array("otras_sal", 		"N", 8, 0),
			array("stock_fin", 		"N", 18, 0),
			array("stock_fin1", 	"N", 18, 0),
			array("req", 			"N", 18, 0),
			array("total", 			"N", 18, 0),
			array("fec_exp", 		"D"),
			array("do_saldo", 		"N", 8, 0),
			array("do_ingre", 		"N", 8, 0),
			array("do_con", 		"N", 8, 0),
			array("do_otr", 		"N", 8, 0),
			array("do_tot", 		"N", 8, 0),
			array("do_stk", 		"N", 8, 0),
			array("do_fecexp", 		"D"),
			array("fecha", 			"D"),
			array("usuario", 		"C", 15),
			array("indiproc", 		"C", 1),
			array("sit", 			"C", 1),
			array("indisiga", 		"C", 1),
			array("dstkcero", 		"N", 8, 0),
			array("mptorepo", 		"N", 10, 2)
		);

		$cabecera_formdetl = array(
			array("codigo_eje", 	"C", 3),
			array("codigo_pre", 	"C", 11),
			array("tipsum", 		"C", 1),
			array("annomes", 		"C", 6),
			array("codigo_med",		"C", 7),
			array("lote",			"C", 15),
			array("fechvto",		"D"),
			array("saldo",			"N", 18, 0),
			array("sit",			"C", 1)
		);

		/**
		 * Creacion del archivo FORMATO.dbf, formdet.dbf
		 */
		if (!dbase_create('export/ici/FORMATO.dbf', $cabecera_formato)) {
				echo "Error, no se puede crear la base de datos";
				exit;
		}

		if (!dbase_create('export/ici/formdet.dbf', $cabecera_formdet)) {
				echo "Error, no se puede crear la base de datos";
				exit;
		}

		if (!dbase_create('export/ici/formdetl.dbf', $cabecera_formdetl)) {
				echo "Error, no se puede crear la base de datos";
				exit;
		}

		if (!dbase_create('export/ici/formdetm.dbf', $cabecera_formdetl)){
            echo "Error, no se puede crear la base de datos";
            exit;
        }

		/**
		 * Ingreso de datos
		 */
		$db_formato = dbase_open('export/ici/FORMATO.dbf', 2);
		$db_formdet = dbase_open('export/ici/formdet.dbf', 2);
		$db_formdetl = dbase_open('export/ici/formdetl.dbf', 2);
		$db_formdetm = dbase_open('export/ici/formdetm.dbf', 2);

		/**
		 * Datos formato
		 */
		if ($db_formato) {
			for ($i=0; $i < count($pre_formato); $i++) {
				dbase_add_record($db_formato, array(
					$pre_formato[$i]['Codigo_eje'],  	# codigo_eje
					$pre_formato[$i]['Codigo_pre'],  	# codigo_pre
					$pre_formato[$i]['Annomes'],  		# annomes
					$pre_formato[$i]['Tipsum'],  		# tipsum
					$pre_formato[$i]['Tipo_pre'],  		# tipo_pre
					$pre_formato[$i]['Rec_vtas'],  		# rec_vtas
					$pre_formato[$i]['Rec_sis'],  		# rec_sis
					$pre_formato[$i]['Rec_ints'],  		# rec_ints
					$pre_formato[$i]['Rec_dn'],  		# rec_dn
					$pre_formato[$i]['Rec_exo'],  		# rec_exo
					$pre_formato[$i]['Rec_soat'],  		# rec_soat
					$pre_formato[$i]['Rec_crehos'],  	# rec_crehos
					$pre_formato[$i]['Rec_otrcon'],  	# rec_otrcon
					$pre_formato[$i]['Indiproc'],  		# indiproc
					$pre_formato[$i]['Fecha'],  		# fecha
					$pre_formato[$i]['Fechault'],  		# fechault
					$pre_formato[$i]['Vers'],  			# vers
					$pre_formato[$i]['Sit'],  			# sit
					$pre_formato[$i]['Fdesde'],  		# fdesde
					$pre_formato[$i]['Fhasta'],  		# fhasta
					$pre_formato[$i]['Ctrlcal'],  		# ctrlcal
					$pre_formato[$i]['Catalogo'],  		# catalogo
					$pre_formato[$i]['Codpto'],  		# codpto
					$pre_formato[$i]['Tip_ins']   		# tip_ins
				));
			}

			dbase_close($db_formato);
			$mensaje = 'Formato generado con éxito';
		}

		//Datos detalle
		if ($db_formdet) {
			for ($x=0; $x < count($pre_formdet); $x++) {
				# stock final
				$stock_fin = 0;
				$stock_fin = ( $pre_formdet[$x]['saldo'] + $pre_formdet[$x]['ingre'] + $pre_formdet[$x]['reingre'] ) - ( $pre_formdet[$x]['ventas'] + $pre_formdet[$x]['sis'] + $pre_formdet[$x]['intersan']+ $pre_formdet[$x]['fac_perd'] + $pre_formdet[$x]['defnac'] + $pre_formdet[$x]['exo'] + $pre_formdet[$x]['soat'] + $pre_formdet[$x]['credhosp'] + $pre_formdet[$x]['otro_conv'] + $pre_formdet[$x]['devol'] + $pre_formdet[$x]['vencido'] + $pre_formdet[$x]['merma'] + $pre_formdet[$x]['distri'] + $pre_formdet[$x]['transf'] + $pre_formdet[$x]['ventainst'] + $pre_formdet[$x]['dev_ven'] + $pre_formdet[$x]['dev_merma'] + $pre_formdet[$x]['otros_sal']);

				$data_codigo_saldos[] = array(
					'Codigo' 	=> trim($pre_formdet[$x]['codigo_med']),
					'stock_fin'	=> $stock_fin
				);

				# requerimiento y total
				$req = 0;
				$total = 0;
				$req = 	$pre_formdet[$x]['ventas'] +
						$pre_formdet[$x]['sis'] +
						$pre_formdet[$x]['intersan'] +
						$pre_formdet[$x]['fac_perd'] +
						$pre_formdet[$x]['defnac'] +
						$pre_formdet[$x]['exo'] +
						$pre_formdet[$x]['soat'] +
						$pre_formdet[$x]['credhosp'] +
						$pre_formdet[$x]['otro_conv'] +
						$pre_formdet[$x]['devol'] +
						$pre_formdet[$x]['vencido'] +
						$pre_formdet[$x]['merma'] +
						$pre_formdet[$x]['distri'] +
						$pre_formdet[$x]['transf'] +
						$pre_formdet[$x]['ventainst'] +
						$pre_formdet[$x]['dev_ven'] +
						$pre_formdet[$x]['dev_merma'] +
						$pre_formdet[$x]['otros_sal'];

				$total =$pre_formdet[$x]['ventas'] +
						$pre_formdet[$x]['sis'] +
						$pre_formdet[$x]['intersan'] +
						$pre_formdet[$x]['fac_perd'] +
						$pre_formdet[$x]['defnac'] +
						$pre_formdet[$x]['exo'] +
						$pre_formdet[$x]['soat'] +
						$pre_formdet[$x]['credhosp'] +
						$pre_formdet[$x]['otro_conv'] +
						$pre_formdet[$x]['devol'] +
						$pre_formdet[$x]['vencido'] +
						$pre_formdet[$x]['merma'] +
						$pre_formdet[$x]['distri'] +
						$pre_formdet[$x]['transf'] +
						$pre_formdet[$x]['ventainst'] +
						$pre_formdet[$x]['dev_ven'] +
						$pre_formdet[$x]['dev_merma'] +
						$pre_formdet[$x]['otros_sal'];
				#echo json_encode($stock_fin);
				# escribiendo archivo
				dbase_add_record($db_formdet,array(
						$pre_formdet[$x]['Codigo_eje'],													#codigo_eje
						$pre_formdet[$x]['Codigo_pre'],													#codigo_pre
						$pre_formdet[$x]['Tipsum'],														#tipsum
						$pre_formdet[$x]['Annomes'],													#annomes
						$pre_formdet[$x]['codigo_med'],													#codigo_med
						$pre_formdet[$x]['saldo'],														#saldo
						number_format($pre_formdet[$x]['precio'], 6, '.', ''),							#precio
						isset($pre_formdet[$x]['ingre']) ? $pre_formdet[$x]['ingre'] : '0',				#ingre
						isset($pre_formdet[$x]['reingre']) ? $pre_formdet[$x]['reingre'] : '0',			#reingre
						isset($pre_formdet[$x]['ventas']) ? $pre_formdet[$x]['ventas'] : '0',			#venta
						isset($pre_formdet[$x]['sis']) ? $pre_formdet[$x]['sis'] : '0',					#sis
						isset($pre_formdet[$x]['intersan']) ? $pre_formdet[$x]['intersan'] : '0',		#intersan
						isset($pre_formdet[$x]['fac_perd']) ? $pre_formdet[$x]['fac_perd'] : '0',		#fac_perd
						isset($pre_formdet[$x]['defnac']) ? $pre_formdet[$x]['defnac'] : '0',			#defnac
						isset($pre_formdet[$x]['exo']) ? $pre_formdet[$x]['exo'] : '0',					#exo
						isset($pre_formdet[$x]['soat']) ? $pre_formdet[$x]['soat'] : '0',				#soat
						isset($pre_formdet[$x]['credhosp']) ? $pre_formdet[$x]['credhosp'] : '0',		#credhosp
						isset($pre_formdet[$x]['otro_conv']) ? $pre_formdet[$x]['otro_conv'] : '0',		#otr_conv
						isset($pre_formdet[$x]['devol']) ? $pre_formdet[$x]['devol'] : '0',				#devol
						isset($pre_formdet[$x]['vencido']) ? $pre_formdet[$x]['vencido'] : '0',			#vencido
						isset($pre_formdet[$x]['merma']) ? $pre_formdet[$x]['merma'] : '0',				#merma
						isset($pre_formdet[$x]['distri']) ? $pre_formdet[$x]['distri'] : '0',			#distri
						isset($pre_formdet[$x]['transf']) ? $pre_formdet[$x]['transf'] : '0',			#transf
						isset($pre_formdet[$x]['ventainst']) ? $pre_formdet[$x]['ventainst'] : '0',		#ventainst
						isset($pre_formdet[$x]['dev_ven']) ? $pre_formdet[$x]['dev_ven'] : '0',			#dev_ven
						isset($pre_formdet[$x]['dev_merma']) ? $pre_formdet[$x]['dev_merma'] : '0',		#dev_merma
						isset($pre_formdet[$x]['otros_sal']) ? $pre_formdet[$x]['otros_sal'] : '0',		#otras_sal
						$stock_fin,																		#stock_fin
						$stock_fin,																		#stock_fin1
						$req,																			#req
						$total,																			#total
						isset($pre_formdet[$x]['fec_exp']) ? $pre_formdet[$x]['fec_exp'] : '01/01/29',	#fec_exp
						'0',																			#do_saldo
						'0',																			#do_ingre
						'0',																			#do_con
						'0',																			#do_otr
						'0',																			#do_tot
						'0',																			#do_stk
						'0',																			#do_fecexp
						date('m/d/y'),																	#fecha
						'',																				#usuario
						'',																				#indiproc
						'1',																			#sit
						'',																				#indisiga
						'0',																			#dstkcero
						'0.00'																			#mptorepo
				));

			}
			dbase_close($db_formdet);
			$mensaje .= '<br>Formtdet generado con éxito';
		}

        $data_codigo_saldos = array_filter($data_codigo_saldos);
        $data_codigo_saldos = array_values($data_codigo_saldos);

        //Datos formdetl
        if ($db_formdetl) {
            for ($j = 0; $j < count($pre_formdetl); $j++ )
            {
                for ($i = 0; $i < count($data_codigo_saldos); $i++ )
                {
                    if (trim($data_codigo_saldos[$i]["Codigo"]) == trim($pre_formdetl[$j]["Codigo"])) {
                        #echo $data_codigo_saldos[$i]["Codigo"] . "|" . $data_codigo_saldos[$i]["stock_fin"]."<br>";
                        dbase_add_record($db_formdetl, array(
                            $pre_formdetl[$j]['Codigo_eje'],
                            $pre_formdetl[$j]['Codigo_pre'],
                            $pre_formdetl[$j]['Tipsum'],
                            $pre_formdetl[$j]['Annomes'],
                            $pre_formdetl[$j]['Codigo'],
                            $pre_formdetl[$j]['Lote'],
                            isset($pre_formdetl[$j]['fec_venc']) ? $pre_formdetl[$j]['fec_venc'] : '01/01/29',
                            $data_codigo_saldos[$i]["stock_fin"],
                            $pre_formdetl[$j]['Sit']
                        ));
                        break;
                    }
                    else{
                        if ($i == count($data_codigo_saldos) - 1) {
                            #echo $pre_formdetl[$j]["Codigo"] . "|0AA<br>";
                            dbase_add_record($db_formdetl, array(
                                $pre_formdetl[$j]['Codigo_eje'],
                                $pre_formdetl[$j]['Codigo_pre'],
                                $pre_formdetl[$j]['Tipsum'],
                                $pre_formdetl[$j]['Annomes'],
                                $pre_formdetl[$j]['Codigo'],
                                $pre_formdetl[$j]['Lote'],
                                isset($pre_formdetl[$j]['fec_venc']) ? $pre_formdetl[$j]['fec_venc'] : '01/01/29',
                                0,
                                $pre_formdetl[$j]['Sit']
                            ));
                            break;
                        }
                    }
                }
            }


			dbase_close($db_formdetl);
			$mensaje .= '<br>Formtdetl generado con éxito';
		}

        //Datos formdetm
        if ($db_formdetm) {
            for ($j = 0; $j < count($pre_formdetm); $j++ )
            {
                for ($i = 0; $i < count($data_codigo_saldos); $i++ )
                {
                    if (trim($data_codigo_saldos[$i]["Codigo"]) == trim($pre_formdetm[$j]["Codigo"])) {
                        #echo $data_codigo_saldos[$i]["Codigo"] . "|" . $data_codigo_saldos[$i]["stock_fin"]."<br>";
                        dbase_add_record($db_formdetm, array(
                            $pre_formdetm[$j]['Codigo_eje'],
                            $pre_formdetm[$j]['Codigo_pre'],
                            $pre_formdetm[$j]['Tipsum'],
                            $pre_formdetm[$j]['Annomes'],
                            $pre_formdetm[$j]['Codigo'],
                            $pre_formdetm[$j]['Lote'],
                            isset($pre_formdetm[$j]['fec_venc']) ? $pre_formdetm[$j]['fec_venc'] : '01/01/29',
                            $data_codigo_saldos[$i]["stock_fin"],
                            $pre_formdetm[$j]['Sit']
                        ));
                        break;
                    }
                    else{
                        if ($i == count($data_codigo_saldos) - 1) {
                            #echo $pre_formdetl[$j]["Codigo"] . "|0AA<br>";
                            dbase_add_record($db_formdetm, array(
                                $pre_formdetm[$j]['Codigo_eje'],
                                $pre_formdetm[$j]['Codigo_pre'],
                                $pre_formdetm[$j]['Tipsum'],
                                $pre_formdetm[$j]['Annomes'],
                                $pre_formdetm[$j]['Codigo'],
                                $pre_formdetm[$j]['Lote'],
                                isset($pre_formdetm[$j]['fec_venc']) ? $pre_formdetm[$j]['fec_venc'] : '01/01/29',
                                0,
                                $pre_formdetm[$j]['Sit']
                            ));
                            break;
                        }
                    }
                }
            }


            dbase_close($db_formdetm);
            $mensaje .= '<br>Formtdetm generado con éxito';

            return response()->json(['correcto' => $mensaje]);
        }
    }

    public function descargar_dbf($nombre)
    {
        $headers = ['Content-Type: application/x-dbase'];
        return response()->download("export/ici/" . $nombre .".dbf", $nombre .".dbf",$headers)->deleteFileAfterSend();
    }
}
