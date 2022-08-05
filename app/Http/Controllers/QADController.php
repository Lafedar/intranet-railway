<?php

namespace App\Http\Controllers;

use App\qad;
use Illuminate\Http\Request;
use App\Exports\OCExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MttRegistrationsExport;
use content\datatable\js\tableToExcel;
//use Maatwebsite\Excel\Excel;


class QADController extends Controller
{
    public function index(Request $request){
        return view('home.qad');
    }
    public function ot(Request $request)
    {
        $ot = $request['ot'];

        $cod_prod = $request['cod_prod'];

        $nom_prod = $request['nom_prod'];

        $lote = $request['lote'];

        $estado = $request['estado'];

        $id=odbc_connect("Driver={Progress OpenEdge 11.3 Driver};Host=10.41.20.142;Port=7140;Database=mfgprod;","root","");


        $sql="SELECT top 100 wo_nbr as ot, wo_lot_next as lote, wo_part as cod_prod, pt_desc1 as nom_prod, wo_status as estado,wo_ord_date as fecha_orden, wo_rel_date as fecha_lib
                FROM PUB.wo_mstr, PUB.pt_mstr 
                where wo_mstr.wo_part = pt_mstr.pt_part and 
                wo_nbr LIKE '%$ot%' and
                wo_part LIKE '%$cod_prod%' and
                pt_desc1 LIKE '%$nom_prod%' and
                wo_lot_next LIKE '%$lote%' and
                wo_status LIKE '%$estado%'";
        $aux = odbc_exec($id, $sql);
        $i=0;

        $row = array();
        while ($a = odbc_fetch_object($aux)) {
            $row[$i][0] = $a->OT;
            $row[$i][1] = $a->COD_PROD;
            $row[$i][2] = $a->NOM_PROD;
            $row[$i][3] = $a->LOTE;
            $row[$i][4] = $a->ESTADO;
            $row[$i][5] = $a->FECHA_ORDEN;
            $row[$i][6] = $a->FECHA_LIB;
            $i=$i+1;
        }
        odbc_free_result($aux);
        odbc_close($id);

        return view ('qad.ot', array('row' => $row,'ot'=> $ot,'cod_prod'=>$cod_prod, 'nom_prod'=>$nom_prod, 'lote' => $lote, 'estado' => $estado ));   
    }

    public function oc(Request $request){
    
        /**return Excel::download(new OCExport($request['orden'],$request['fecha_desde'], $request['fecha_hasta'], $request['nro_proveedor'], $request['cuit'],
        $request['nro_articulo'] ), 'oc.xlsx' );
    **/
//-----------------intento de jorge-------------------------------------
//----------------------------------------------------------------------        


        $oc = $request['oc'];
        $fecha1 = $request['fecha1'];
        $lin = $request['lin'];
        $prov = $request['prov'];
        $cuit_prov = $request['cuit_prov'];
        $datos_prov = $request['datos_prov'];
        $requi = $request['requi'];
        $solicitado_x = $request['solicitado_x'];
        $decrip = $request['decrip'];
        $art = $request['art'];
        $descrip =$request['descrip'];
        $moneda = $request['moneda'];
        $cambio = $request['cambio'];
        $costo_compras = $request['costo_compras'];
        $cant_ord = $request['cant_ord'];
       // $cant_pend = $request['cant_pend'];
        $um = $request['um'];
        $fecha_venc = $request['fecha_venc'];
        $id_ot = $request['id_ot'];
        $es = $request['es'];
        $fecha_cierre = $request['fecha_cierre'];
        $opcion = $request['opcion'];
        $opcion_lis = $request['opcion_lis'];
        $comentario = $request['comentario'];
        $control =$request['control'];
        $recibido =$request['recibido'];
        $fecha = $request['fecha'];
       
        

       

  

        $id=odbc_connect("Driver={Progress OpenEdge 11.3 Driver};Host=10.41.20.142;Port=7140;Database=mfgprod;","root","");

  
        $sql = "SELECT pod_nbr as oc, po_ord_date as fecha1, po_cls_date as fecha_cierre, pod_due_date as fecha_venc, pod_part as art, pod_qty_ord as cant_ord, pod_line as lin,po_vend as prov, vd_sort as datos_prov, pod_req_nbr as requi, po_req_id as solicitado_x,  po_curr as moneda, po_ex_rate2 as cambio, pod_pur_cost as costo_compras, pod_qty_ord as cant_ord, pod_um as um, po_req_id as solictado_x, po_curr as moneda, po_ex_rate2 as cambio, pod_pur_cost as costo_compras, pod_qty_ord as cant_ord, pod_um as um, po_due_date as fecha_venc, pod_wo_lot as id_ot, pod_status as es, po_cls_date as fecha_cierre, pod_ers_opt as opcion, pod_pr_lst_tp as opcion_lis,pod_qty_rcvd as recibido
        , cmt_cmmt[01] + ' ' + cmt_cmmt[02] + ' ' + cmt_cmmt[03] + ' ' + cmt_cmmt[04] + ' ' + cmt_cmmt[05] + ' ' + cmt_cmmt[06] + ' ' + cmt_cmmt[07] + ' ' + cmt_cmmt[08] + ' ' + cmt_cmmt[09] + ' ' + cmt_cmmt[10] + ' ' + cmt_cmmt[11] + ' ' + cmt_cmmt[12] + ' ' + cmt_cmmt[13] + ' ' + cmt_cmmt[14] + ' ' + cmt_cmmt[15] as comentario,ad_gst_id as cuit_prov, pt_desc1 as descrip
        
  
            FROM PUB.pod_det LEFT JOIN PUB.cmt_det on pod_det.pod_cmtindx = cmt_det.cmt_indx and pod_det.pod_domain = cmt_det.cmt_domain
                             left join PUB.pt_mstr on pod_det.pod_part=pt_mstr.pt_part and pod_det.pod_domain =pt_mstr.pt_domain
                            join PUB.po_mstr on pod_det.pod_nbr = po_mstr.po_nbr and pod_det.pod_domain=po_mstr.po_domain
            join PUB.vd_mstr on po_mstr.po_vend = vd_mstr.vd_addr and po_mstr.po_domain = vd_mstr.vd_domain
            join PUB.ad_mstr on vd_mstr.vd_domain = ad_mstr.ad_addr and vd_mstr.vd_domain = ad_mstr.ad_domain           
                             
           where  pod_nbr LIKE '%$oc%' and
                  pod_part LIKE '%$art%' and
                  po_ord_date is not null --and
                --pod_due_date is not null
                ";
      // dd($fecha_actual, $fecha_comp);
 
        $aux = odbc_exec($id, $sql);
        $i=0;

        $row = array();
        while ($a = odbc_fetch_object($aux)) {
            $row[$i][0] = $a->OC;
            $row[$i][1] = $a->FECHA1;
            $row[$i][2] = $a->LIN;
            $row[$i][3] = $a->PROV;
            $row[$i][4] = $a->CUIT_PROV;
            $row[$i][5] = $a->DATOS_PROV;
            $row[$i][6] = $a->REQUI;
            $row[$i][7] = $a->SOLICITADO_X;
            $row[$i][8] = $a->ART;
            $row[$i][9] = $a->DESCRIP;
            $row[$i][10] = $a->MONEDA;
            $row[$i][11] = $a->CAMBIO;
            $row[$i][12] = $a->COSTO_COMPRAS;
            $row[$i][13] = $a->CANT_ORD;
            $row[$i][14] = $a->RECIBIDO;
            $row[$i][15] = $a->UM;
            $row[$i][16] = $a->FECHA_VENC;
            $row[$i][17] = $a->ID_OT;
            $row[$i][18] = $a->ES;
            $row[$i][19] = $a->FECHA_CIERRE;
            $row[$i][20] = $a->OPCION;
            $row[$i][21] = $a->OPCION_LIS;
            $row[$i][22] = $a->COMENTARIO;
            
            $i=$i+1;
            
        }
     
        //dd($i);
        odbc_free_result($aux);
        odbc_close($id);
       
        return view ('qad.oc', array('row' => $row,'oc'=> $oc,'fecha1' => $fecha1,'lin'=>$lin, 'prov'=>$prov,'cuit_prov'=>$cuit_prov,'datos_prov'=>$datos_prov,'requi'=>$requi,'solicitado_x'=>$solicitado_x, 'art' => $art,'moneda'=>$moneda, 'cambio'=>$cambio,'costo_compras'=>$costo_compras,'cant_ord'=>$cant_ord,'um'=>$um,'fecha_venc' => $fecha_venc, 'id_ot'=>$id_ot,'es'=>$es, 'fecha_cierre'=>$fecha_cierre, 'opcion'=>$opcion, 'opcion_lis'=>$opcion_lis,'fecha' =>$fecha, 'recibido'=>$recibido, 'comenterio'=>$comentario, 'descrip'=>$descrip));   
 
    }
   
   public function foc(Request $request)
    { 
           

    }
    public function create()
    {
        //
    }
  public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
