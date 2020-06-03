<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Response;
use DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Image;
use File;
use Mail;
use App\Page;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('permission:statistics', ['only' => [
        //     'patientstatistics',
        // ]]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function deleteallimage($ids) {
        $ids = explode(',', $ids);

        foreach ($ids as $id) {
            $p = ProjectImage::findOrFail($id);
            $img_path = base_path() . '/uploads/project_images/source/';
            $img_path200 = base_path() . '/uploads/project_images/resize200/';
            $img_path800 = base_path() . '/uploads/project_images/resize800/';

            unlink(sprintf($img_path . '%s', $p->image));
            unlink(sprintf($img_path200 . '%s', $p->image));
            unlink(sprintf($img_path800 . '%s', $p->image));

            $p->delete();
        }
    }
    
    public function deleteoneimage($id) {

        $p = ProjectImage::findOrFail($id);
        $img_path = base_path() . '/uploads/project_images/source/';
        $img_path200 = base_path() . '/uploads/project_images/resize200/';
        $img_path800 = base_path() . '/uploads/project_images/resize800/';

        unlink(sprintf($img_path . '%s', $p->image));
        unlink(sprintf($img_path200 . '%s', $p->image));
        unlink(sprintf($img_path800 . '%s', $p->image));

        $p->delete();
    }

    public function getrefferalvalue()
    {
        $refferal = $_POST['refferal'];
        $total = $_POST['total'];
        
        $r = Refferal::where('id',$refferal)->first();
        if($r->reff_discount_val == 0){
            $re = ($r->reff_discount_per)*100 .'%';
            $red = ($r->reff_discount_per)*100 ;
            $result = ($total * $r->reff_discount_per);
            $actual = ($total - $result);
        }else{
             $re = $r->reff_discount_val;
             $red = $r->reff_discount_val;
             $actual = $total - $r->reff_discount_val;
        }
        return Response::json([$re,$red,$actual]);
    }

    
    public function selectdayoutcome()
    {
        $dayselect = $_POST['dayselect'];
        $w = explode('-', $dayselect);
        
        $day = $w[2];
        $month = $w[1];
        $year = $w[0];

        $salary = Accounting::where('acc_type','salary')->where( DB::raw('DAY(created_at)'), '=', $day )->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));
        $equipment = Accounting::where('acc_type','equipment')->where( DB::raw('DAY(created_at)'), '=', $day )->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));
        $service = Accounting::where('acc_type','service')->where( DB::raw('DAY(created_at)'), '=', $day )->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));
        $other = Accounting::where('acc_type','other')->where( DB::raw('DAY(created_at)'), '=', $day )->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));

        $total = round($salary + $equipment + $service + $other) ;
        return Response::json([$salary,$equipment,$service,$other,$total]);
    }

     public function selectmonthoutcome()
    {
        $monthselect = $_POST['monthselect'];
        $w = explode('-', $monthselect);
        $month = $w[1];
        $year = $w[0];

        $salary = Accounting::where('acc_type','salary')->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));
        $equipment = Accounting::where('acc_type','equipment')->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));
        $service = Accounting::where('acc_type','service')->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));
        $other = Accounting::where('acc_type','other')->where( DB::raw('MONTH(created_at)'), '=', $month )->where( DB::raw('YEAR(created_at)'), '=', $year )->sum(DB::raw('acc_value'));

        $total = round($salary + $equipment + $service + $other) ;
        return Response::json([$salary,$equipment,$service,$other,$total]);
    }



    public function selectyearoutcome()
    {
        $yearselect = $_POST['yearselect'];

        $salary = Accounting::where('acc_type','salary')->where( DB::raw('YEAR(created_at)'), '=', $yearselect )->sum(DB::raw('acc_value'));
        $equipment = Accounting::where('acc_type','equipment')->where( DB::raw('YEAR(created_at)'), '=', $yearselect )->sum(DB::raw('acc_value'));
        $service = Accounting::where('acc_type','service')->where( DB::raw('YEAR(created_at)'), '=', $yearselect )->sum(DB::raw('acc_value'));
        $other = Accounting::where('acc_type','other')->where( DB::raw('YEAR(created_at)'), '=', $yearselect )->sum(DB::raw('acc_value'));

        $total = round($salary + $equipment + $service + $other) ;
        return Response::json([$salary,$equipment,$service,$other,$total]);
    }
    public function selectdayincome()
    {
        $dayselect = $_POST['dayselect'];
        $w = explode('-', $dayselect);
        
        $day = $w[2];
        $month = $w[1];
        $year = $w[0];

        $actualpaid = Visit::where( DB::raw('DAY(date)'), '=', $day )->where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('vis_actual_paid'));
        $analysiscommission = Visit::where( DB::raw('DAY(date)'), '=', $day )->where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('analysis_commission'));
        $rayscommission = Visit::where( DB::raw('DAY(date)'), '=', $day )->where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('rays_commission'));
        $medicinecommission = Visit::where( DB::raw('DAY(date)'), '=', $day )->where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('medicine_commission'));

 $services = Servicepatient::LeftJoin('visit','service_patient.vis_id','=','visit.id')->where( DB::raw('DAY(visit.date)'), '=', $day )->where( DB::raw('MONTH(visit.date)'), '=', $month )->where( DB::raw('YEAR(visit.date)'), '=', $year ) ->sum(DB::raw('service_patient.price'));
 
        $total = round($actualpaid + $analysiscommission + $rayscommission + $medicinecommission + $services) ;
        return Response::json([$actualpaid,$analysiscommission,$rayscommission,$medicinecommission,$services,$total]);
    }
    public function selectmonthincome()
    {
        $monthselect = $_POST['monthselect'];
        $w = explode('-', $monthselect);
        $month = $w[1];
        $year = $w[0];

        $actualpaid = Visit::where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('vis_actual_paid'));
        $analysiscommission = Visit::where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('analysis_commission'));
        $rayscommission = Visit::where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('rays_commission'));
        $medicinecommission = Visit::where( DB::raw('MONTH(date)'), '=', $month )->where( DB::raw('YEAR(date)'), '=', $year )->sum(DB::raw('medicine_commission'));
        
        $services = Servicepatient::LeftJoin('visit','service_patient.vis_id','=','visit.id')->where( DB::raw('MONTH(visit.date)'), '=', $month )->where( DB::raw('YEAR(visit.date)'), '=', $year ) ->sum(DB::raw('service_patient.price'));

        
        $total = round($actualpaid + $analysiscommission + $rayscommission + $medicinecommission + $services) ;
        return Response::json([$actualpaid,$analysiscommission,$rayscommission,$medicinecommission,$services,$total]);
    }
    public function selectyearincome()
    {
         $yearselect = $_POST['yearselect'];

        $actualpaid = Visit::where( DB::raw('YEAR(date)'), '=', $yearselect )->sum(DB::raw('vis_actual_paid'));
        $analysiscommission = Visit::where( DB::raw('YEAR(date)'), '=', $yearselect )->sum(DB::raw('analysis_commission'));
        $rayscommission = Visit::where( DB::raw('YEAR(date)'), '=', $yearselect )->sum(DB::raw('rays_commission'));
        $medicinecommission = Visit::where( DB::raw('YEAR(date)'), '=', $yearselect )->sum(DB::raw('medicine_commission'));

$services = Servicepatient::LeftJoin('visit','service_patient.vis_id','=','visit.id')->where( DB::raw('YEAR(visit.date)'), '=', $yearselect ) ->sum(DB::raw('service_patient.price'));

        $total = round($actualpaid + $analysiscommission + $rayscommission + $medicinecommission + $services) ;
        return Response::json([$actualpaid,$analysiscommission,$rayscommission,$medicinecommission,$services,$total]);
        
    }


    public function income()
    {
        $v = Visit::all();
        return view('medical.accounting.income',compact('v'));
    }

    public function outcome()
    {
        return view('medical.accounting.outcome');
    }

    public function patientstatistics()
    {
        $p = DB::table('patient')->lists('patient_name','id');
        return view('medical.statistics.patientstatistics',compact('p'));
    }
    public function getpatientinfo()
    {
        $id = $_POST['pa'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $day = $ww[2];
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $dayend = $www[2];
        $monthend = $www[1];
        $yearend = $www[0];

        $checked = $_POST['checkbox_value'];
        $arr = explode('|', $checked);

                        $diagname = array();
                        $diagdate = array();
                        $medname = array();
                        $meddate = array();
                        $analname = array();
                        $analdate = array();
                        $analnormal = array();
                        $analresult = array();
                        $dianameana = array();
                        $rayname = array();
                        $raydate = array();
                        $dianamemed = array();
                        $dianameray = array();

        for ($i=0; $i <count($arr) ; $i++) { 
            if ($arr[$i] == 'dia') {
                $diag = Visit::where('patient_id',$id)->where( DB::raw('DAY(date)'), '>=', $day )->where( DB::raw('MONTH(date)'), '>=', $month )->where( DB::raw('YEAR(date)'), '>=', $year )->where( DB::raw('DAY(date)'), '<=', $dayend )->where( DB::raw('MONTH(date)'), '<=', $monthend )->where( DB::raw('YEAR(date)'), '<=', $yearend )->select('date','diagnosis_id')->get();
                $diagcount = count($diag);
                if ($diagcount == '0') {
                    $diagname = array();
                    $diagdate = array();
                }else{
                    for ($w=0; $w <count($diag) ; $w++) { 
                        $diagname[] =  $diag[$w]->Diagnosis->diag_name;
                        $diagdate[] = date('Y-m-d', strtotime($diag[$w]->date));
                    }
                }
            }
            
            if ($arr[$i] == 'med') {
                $patientmedicine = Medvisit::LeftJoin('visit','medicine_visit.vis_id','=','visit.id')->where('visit.patient_id',$id)->where( DB::raw('DAY(medicine_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(medicine_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(medicine_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(medicine_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(medicine_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(medicine_visit.created_at)'), '<=', $yearend )->with('Medicine')->select('medicine_visit.created_at','medicine_visit.med_id','visit.diagnosis_id')->get();

                $medcount = count($patientmedicine);

                    if ($medcount == '0' ) {
                        $medname = array();
                        $meddate = array();
                        $dianamemed = array();
                    }else{
                        for ($t=0; $t <count($patientmedicine) ; $t++) { 
                            $medname[] =  $patientmedicine[$t]->Medicine->med_name;
                            $meddate[] = date('Y-m-d', strtotime($patientmedicine[$t]->created_at));
                            $dianamemed[] = Diagnosis::where('id',$patientmedicine[$t]->diagnosis_id)->select('diag_name')->first();
                        }
                    }    
            }
            
            if ($arr[$i]== 'ana') {
                $patientanalysis = Analysisvisit::LeftJoin('visit','analysis_visit.vis_id','=','visit.id')->where('visit.patient_id',$id)->where( DB::raw('DAY(analysis_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(analysis_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(analysis_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(analysis_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(analysis_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(analysis_visit.created_at)'), '<=', $yearend )->with('Analysis')->select('analysis_visit.created_at','analysis_visit.anal_id','visit.diagnosis_id','analysis_visit.anal_normal','analysis_visit.anal_result')->get();
                
                $anacount = count($patientanalysis);
                    if ($anacount == '0') {
                        $analname = array();
                        $analdate = array();
                        $analnormal = array();
                        $analresult = array();
                        $dianameana = array();
                    }else{
                        for ($q=0; $q <count($patientanalysis) ; $q++) { 
                            $analname[] =  $patientanalysis[$q]->Analysis->anal_name;
                            $analnormal[] =  $patientanalysis[$q]->anal_normal;
                            $analresult[] =  $patientanalysis[$q]->anal_result;
                            $analdate[] = date('Y-m-d', strtotime($patientanalysis[$q]->created_at));
                            $dianameana[] = Diagnosis::where('id',$patientanalysis[$q]->diagnosis_id)->select('diag_name')->first();
                        }
                    }
            }
            if ($arr[$i] == 'ray') {
                $patientrays = Raysvisit::LeftJoin('visit','rays_visit.vis_id','=','visit.id')->where('visit.patient_id',$id)->where( DB::raw('DAY(rays_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(rays_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(rays_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(rays_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(rays_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(rays_visit.created_at)'), '<=', $yearend )->with('Imagray')->select('rays_visit.created_at','rays_visit.rays_id','visit.diagnosis_id')->get();
                $raycount = count($patientrays);
                
                if ($raycount == '0') {
                    $rayname = array();
                    $raydate = array();
                    $dianameray = array();
                }else{
                    for ($e=0; $e <count($patientrays) ; $e++) { 
                        $rayname[] =  $patientrays[$e]->Rays->rays_name;
                        $raydate[] = date('Y-m-d', strtotime($patientrays[$e]->created_at));
                        $dianameray[] = Diagnosis::where('id',$patientrays[$e]->diagnosis_id)->select('diag_name')->first();
                    }
                }
            }
            
        }

        return Response::json([$diagname,$diagdate,$medname,$meddate,$analname,$dianameana,$analdate,$analnormal,$analresult,$rayname,$raydate,$dianamemed,$dianameray]);
    }
    public function diagnosisstatistics()
    {
        $d = DB::table('diagnosis')->lists('diag_name','id');
        return view('medical.statistics.diagnosisstatistics',compact('d'));
    }

    public function getdiagnosisinfo()
    {
        $diaid = $_POST['dia'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $day = $ww[2];
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $dayend = $www[2];
        $monthend = $www[1];
        $yearend = $www[0];

        $diacount = visit::where('diagnosis_id',$diaid)->where( DB::raw('DAY(visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(visit.created_at)'), '>=', $year )->where( DB::raw('DAY(visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(visit.created_at)'), '<=', $yearend )->count();

        $diaganalysis = Analysisvisit::LeftJoin('visit','analysis_visit.vis_id','=','visit.id')->where('visit.diagnosis_id',$diaid)->where( DB::raw('DAY(analysis_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(analysis_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(analysis_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(analysis_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(analysis_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(analysis_visit.created_at)'), '<=', $yearend )->with('Analysis')->select('analysis_visit.anal_id')->groupBy('analysis_visit.anal_id')->get();
        $anacount = count($diaganalysis);
            if ($anacount == '0') {
                $analname = array('No Result');
            }else{
                for ($q=0; $q <count($diaganalysis) ; $q++) { 
                    $analname[] =  $diaganalysis[$q]->Analysis->anal_name;
                }
            }
        $ananame = implode(', ', $analname);

        $diagmedicine = Medvisit::LeftJoin('visit','medicine_visit.vis_id','=','visit.id')->where('visit.diagnosis_id',$diaid)->where( DB::raw('DAY(medicine_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(medicine_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(medicine_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(medicine_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(medicine_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(medicine_visit.created_at)'), '<=', $yearend )->with('Medicine')->select('medicine_visit.med_id')->groupBy('medicine_visit.med_id')->get();  
        $medcount = count($diagmedicine);

            if ($medcount == '0' ) {
                $medname = array('No Result');
            }else{
                for ($t=0; $t <count($diagmedicine) ; $t++) { 
                    $medname[] =  $diagmedicine[$t]->Medicine->med_name;
                }
            }   
        $mname = implode(', ', $medname);      

        $diagrays = Raysvisit::LeftJoin('visit','rays_visit.vis_id','=','visit.id')->where('visit.diagnosis_id',$diaid)->where( DB::raw('DAY(rays_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(rays_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(rays_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(rays_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(rays_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(rays_visit.created_at)'), '<=', $yearend )->with('Imagray')->select('rays_visit.rays_id')->get();
        $raycount = count($diagrays);
                
            if ($raycount == '0') {
                $rayname = array('No Result');
            }else{
                for ($e=0; $e <count($diagrays) ; $e++) { 
                    $rayname[] =  $diagrays[$e]->Rays->rays_name;
                }
            }
        $raysname = implode(', ', $rayname); 
        
        return Response::json([$diacount,$ananame,$mname,$raysname]);
    }

    public function analysisstatistics()
    {
        $a = DB::table('analysis')->lists('anal_name','id');
        return view('medical.statistics.analysisstatistics',compact('a'));
    }

    public function getanalysisinfo(){
        $anaid = $_POST['ana'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $day = $ww[2];
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $dayend = $www[2];
        $monthend = $www[1];
        $yearend = $www[0];

        // $anacount = Analysisvisit::where('anal_id',$anaid)->where( DB::raw('DAY(created_at)'), '>=', $day )->where( DB::raw('MONTH(created_at)'), '>=', $month )->where( DB::raw('YEAR(created_at)'), '>=', $year )->where( DB::raw('DAY(created_at)'), '<=', $dayend )->where( DB::raw('MONTH(created_at)'), '<=', $monthend )->where( DB::raw('YEAR(created_at)'), '<=', $yearend )->count();

        $diaganalysis = Analysisvisit::LeftJoin('visit','analysis_visit.vis_id','=','visit.id')->where('analysis_visit.anal_id',$anaid)->where( DB::raw('DAY(analysis_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(analysis_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(analysis_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(analysis_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(analysis_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(analysis_visit.created_at)'), '<=', $yearend )->with('Analysis')->select('visit.diagnosis_id')->groupBy('diagnosis_id')->get();
        $anacount = count($diaganalysis);
            if ($anacount == '0') {
                $digname = array('No Result');
            }else{
                for ($q=0; $q <count($diaganalysis) ; $q++) {
                    $di[] = Diagnosis::where('id',$diaganalysis[$q]->diagnosis_id)->select('diag_name')->first();
                    $digname[] = $di[$q]->diag_name;
                }
                
            }
        $diagname = implode(', ', $digname);

        $visitanalysis = Analysisvisit::LeftJoin('visit','analysis_visit.vis_id','=','visit.id')->where('analysis_visit.anal_id',$anaid)->where( DB::raw('DAY(analysis_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(analysis_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(analysis_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(analysis_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(analysis_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(analysis_visit.created_at)'), '<=', $yearend )->pluck('visit.id');

        for ($t=0; $t <count($visitanalysis) ; $t++) { 
            $anamedicine[] = Medvisit::where('vis_id',$visitanalysis[$t])->with('Medicine')->select('med_id')->get();
        }
        $medcount = count($visitanalysis);

            if ($medcount == '0' ) {
                $medname = array('No Result');
            }else{
                for ($t=0; $t <count($anamedicine) ; $t++) { 
                    for ($u=0; $u <count($anamedicine[$t]) ; $u++) { 
                        $medname[] =  $anamedicine[$t][$u]->Medicine->med_name;
                    } 
                }
                
            }   
            
        $mname = implode(', ', array_unique($medname));

        for ($t=0; $t <count($visitanalysis) ; $t++) { 
            $anarays[] = Raysvisit::where('vis_id',$visitanalysis[$t])->with('Rays')->select('rays_id')->get();
        }
        //$medcount = count($visitanalysis);

            if ($medcount == '0' ) {
                $rayname = array('No Result');
            }else{
                for ($p=0; $p <count($anarays) ; $p++) { 
                    for ($oo=0; $oo <count($anarays[$p]) ; $oo++) { 
                        $rayname[] =  $anarays[$p][$oo]->Rays->rays_name;
                    } 
                }
            }   
        $rname = implode(', ', array_unique($rayname));

        return Response::json([$medcount,$diagname,$mname,$rname]);
    }

    public function medicinestatistics()
    {
        $m = DB::table('medicine')->lists('med_name','id');
        return view('medical.statistics.medicinestatistics',compact('m'));
    }
    public function getmedicineinfo()
    {
        $medid = $_POST['med'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $day = $ww[2];
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $dayend = $www[2];
        $monthend = $www[1];
        $yearend = $www[0];

        $diagmedicine = Medvisit::LeftJoin('visit','medicine_visit.vis_id','=','visit.id')->where('medicine_visit.med_id',$medid)->where( DB::raw('DAY(medicine_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(medicine_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(medicine_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(medicine_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(medicine_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(medicine_visit.created_at)'), '<=', $yearend )->with('Medicine')->select('visit.diagnosis_id')->groupBy('diagnosis_id')->get();  
        $medcount = count($diagmedicine);

            if ($medcount == '0') {
                $digname = array('No Result');
            }else{
                for ($q=0; $q <count($diagmedicine) ; $q++) {
                    $di[] = Diagnosis::where('id',$diagmedicine[$q]->diagnosis_id)->select('diag_name')->first();
                    $digname[] = $di[$q]->diag_name;
                }
                
            }
        $diagname = implode(', ', $digname);

        $visitmedicine = Medvisit::LeftJoin('visit','medicine_visit.vis_id','=','visit.id')->where('medicine_visit.med_id',$medid)->where( DB::raw('DAY(medicine_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(medicine_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(medicine_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(medicine_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(medicine_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(medicine_visit.created_at)'), '<=', $yearend )->pluck('visit.id');


        for ($t=0; $t <count($visitmedicine) ; $t++) { 
            $anamedicine[] = Analysisvisit::where('vis_id',$visitmedicine[$t])->with('Analysis')->select('anal_id')->get();
        }
        $analcount = count($visitmedicine);

            if ($analcount == '0' ) {
                $analname = array('No Result');
            }else{
                for ($t=0; $t <count($anamedicine) ; $t++) { 
                    for ($u=0; $u <count($anamedicine[$t]) ; $u++) { 
                        $analname[] =  $anamedicine[$t][$u]->Analysis->anal_name;
                    } 
                }
                
            }   
            
        $aname = implode(', ', array_unique($analname));

        for ($tt=0; $tt <count($visitmedicine) ; $tt++) { 
            $medrays[] = Raysvisit::where('vis_id',$visitmedicine[$tt])->with('Rays')->select('rays_id')->get();
        }
        //$medcount = count($visitanalysis);

            if ($analcount == '0' ) {
                $rayname = array('No Result');
            }else{
                for ($p=0; $p <count($medrays) ; $p++) { 
                    for ($oo=0; $oo <count($medrays[$p]) ; $oo++) { 
                        $rayname[] =  $medrays[$p][$oo]->Rays->rays_name;
                    } 
                }
            }   
        $rname = implode(', ', array_unique($rayname));

        return Response::json([$analcount,$diagname,$aname,$rname]);
    }

    public function raystatistics()
    {
        $r = DB::table('rays')->lists('rays_name','id');
        return view('medical.statistics.raystatistics',compact('r'));
    }
    public function getrayinfo()
    {
        $rayid = $_POST['ray'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $day = $ww[2];
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $dayend = $www[2];
        $monthend = $www[1];
        $yearend = $www[0];

        $diagrays = Raysvisit::LeftJoin('visit','rays_visit.vis_id','=','visit.id')->where('rays_visit.rays_id',$rayid)->where( DB::raw('DAY(rays_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(rays_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(rays_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(rays_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(rays_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(rays_visit.created_at)'), '<=', $yearend )->with('Rays')->select('visit.diagnosis_id')->groupBy('diagnosis_id')->get();  
        $dicount = count($diagrays);

            if ($dicount == '0') {
                $digname = array('No Result');
            }else{
                for ($q=0; $q <count($diagrays) ; $q++) {
                    $di[] = Diagnosis::where('id',$diagrays[$q]->diagnosis_id)->select('diag_name')->first();
                    $digname[] = $di[$q]->diag_name;
                }
                
            }
        $diagname = implode(', ', $digname);

        $visitrays = Raysvisit::LeftJoin('visit','rays_visit.vis_id','=','visit.id')->where('rays_visit.rays_id',$rayid)->where( DB::raw('DAY(rays_visit.created_at)'), '>=', $day )->where( DB::raw('MONTH(rays_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(rays_visit.created_at)'), '>=', $year )->where( DB::raw('DAY(rays_visit.created_at)'), '<=', $dayend )->where( DB::raw('MONTH(rays_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(rays_visit.created_at)'), '<=', $yearend )->pluck('visit.id');

        for ($t=0; $t <count($visitrays) ; $t++) { 
            $raymedicine[] = Medvisit::where('vis_id',$visitrays[$t])->with('Medicine')->select('med_id')->get();
        }
        $raycount = count($visitrays);

            if ($raycount == '0' ) {
                $medname = array('No Result');
            }else{
                for ($t=0; $t <count($raymedicine) ; $t++) { 
                    for ($u=0; $u <count($raymedicine[$t]) ; $u++) { 
                        $medname[] =  $raymedicine[$t][$u]->Medicine->med_name;
                    } 
                }
                
            }   
            
        $mname = implode(', ', array_unique($medname));

        for ($t=0; $t <count($visitrays) ; $t++) { 
            $anarays[] = Analysisvisit::where('vis_id',$visitrays[$t])->with('Analysis')->select('anal_id')->get();
        }

            if ($raycount == '0' ) {
                $analname = array('No Result');
            }else{
                for ($t=0; $t <count($anarays) ; $t++) { 
                    for ($u=0; $u <count($anarays[$t]) ; $u++) { 
                        $analname[] =  $anarays[$t][$u]->Analysis->anal_name;
                    } 
                }
                
            }   
            
        $aname = implode(', ', array_unique($analname));

        return Response::json([$raycount,$diagname,$mname,$aname]);
    }
    public function commission()
    {
       return view('medical.accounting.commission');
    }
    public function servicesaccounting()
    {
       return view('medical.accounting.services');
    }
    public function servicesaccount()
    {
      $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $monthend = $www[1];
        $yearend = $www[0];
       $services = Servicepatient::LeftJoin('visit','service_patient.vis_id','=','visit.id')->where( DB::raw('MONTH(visit.date)'), '>=', $month )->where( DB::raw('YEAR(visit.date)'), '>=', $year )->where( DB::raw('MONTH(visit.date)'), '<=', $monthend )->where( DB::raw('YEAR(visit.date)'), '<=', $yearend )->groupBy('service_patient.service_id')->select(DB::raw('sum(service_patient.price) AS price'),'service_patient.service_id',DB::raw('count(service_patient.service_id) AS id'))->get();
        for ($i=0; $i <count($services) ; $i++) { 
            $clinicname[] = $services[$i]->Ser->name;
            $clinicvisit[] = $services[$i]->id;
            $clinicprice[] = $services[$i]->price;
           
        }
        if (count($services) == 0) {
            $clinicname[] = '';
            $clinicvisit[] = 0;
            $clinicprice[] = 0;
           
        }
        return Response::json([$clinicname,$clinicvisit,$clinicprice]);
    }
    public function laboratorycommission()
    {
        
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $monthend = $www[1];
        $yearend = $www[0];

        

        $labvisit = Analysisvisit::LeftJoin('visit','analysis_visit.vis_id','=','visit.id')->where( DB::raw('MONTH(analysis_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(analysis_visit.created_at)'), '>=', $year )->where( DB::raw('MONTH(analysis_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(analysis_visit.created_at)'), '<=', $yearend )->with('Lab')->select('analysis_visit.vis_id','analysis_visit.lab_id','visit.analysis_commission')->groupBy('analysis_visit.vis_id')->get();
        $labid = 0;
        $total = [];
        $labvisitcount = count($labvisit);
        if ($labvisitcount == 0) {
            $all['null'] = 'No Result';
        }else{
            for ($q=0; $q <count($labvisit) ; $q++) { 
                if ($labvisit[$q]['lab_id'] == $labid) {
                    $total[$labvisit[$q]['lab_id']] +=  $labvisit[$q]['analysis_commission'];
                }else{
                    $total[$labvisit[$q]['lab_id']] =  $labvisit[$q]['analysis_commission'];
                }
                    $labid = $labvisit[$q]['lab_id'];
            }
            for ($i=0; $i <count($total) ; $i++) { 
               
            }
            foreach ($total as $key => $value) {
                $labname =Laboratory::where('id',$key)->select('lab_name')->first();

                $all[$labname->lab_name] = $value;
            }
            
        }

        
        return Response::json($all);
    }


    public function pharmacycommission()
    {
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $monthend = $www[1];
        $yearend = $www[0];

        

        $pharmvisit = Medvisit::LeftJoin('visit','medicine_visit.vis_id','=','visit.id')->where( DB::raw('MONTH(medicine_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(medicine_visit.created_at)'), '>=', $year )->where( DB::raw('MONTH(medicine_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(medicine_visit.created_at)'), '<=', $yearend )->select('medicine_visit.vis_id','medicine_visit.pharm_id','visit.medicine_commission')->groupBy('medicine_visit.vis_id')->get();
        $pharmid = 0;
        $pharmvisitcount = count($pharmvisit);
        if ($pharmvisitcount == 0) {
            $all['null'] = 'No Result';
        }else{
            for ($q=0; $q <count($pharmvisit) ; $q++) { 
                if ($pharmvisit[$q]['pharm_id'] == $pharmid) {
                    $total[$pharmvisit[$q]['pharm_id']] +=  $pharmvisit[$q]['medicine_commission'];
                }else{
                    $total[$pharmvisit[$q]['pharm_id']] =  $pharmvisit[$q]['medicine_commission'];
                }
                    $pharmid = $pharmvisit[$q]['pharm_id'];
            }
            for ($i=0; $i <count($total) ; $i++) { 
               
            }
            foreach ($total as $key => $value) {
                $pharmname =Pharmacy::where('id',$key)->select('pharm_name')->first();

                $all[$pharmname->pharm_name] = $value;
            }
            
        }

        
        return Response::json($all);
    }

    public function rayscentercommission()
    {
        $start = $_POST['start'];
        $end = $_POST['end'];

        $ww = explode('-', $start);
        $month = $ww[1];
        $year = $ww[0];

        $www = explode('-', $end);
        $monthend = $www[1];
        $yearend = $www[0];

        

        $centervisit = Raysvisit::LeftJoin('visit','rays_visit.vis_id','=','visit.id')->where( DB::raw('MONTH(rays_visit.created_at)'), '>=', $month )->where( DB::raw('YEAR(rays_visit.created_at)'), '>=', $year )->where( DB::raw('MONTH(rays_visit.created_at)'), '<=', $monthend )->where( DB::raw('YEAR(rays_visit.created_at)'), '<=', $yearend )->select('rays_visit.vis_id','rays_visit.rays_center_id','visit.rays_commission')->groupBy('rays_visit.vis_id')->get();
        $centerid = 0;
        $centervisitcount = count($centervisit);
        if ($centervisitcount == 0) {
            $all['null'] = 'No Result';
        }else{
            for ($q=0; $q <count($centervisit) ; $q++) { 
                if ($centervisit[$q]['rays_center_id'] == $centerid) {
                    $total[$centervisit[$q]['rays_center_id']] +=  $centervisit[$q]['rays_commission'];
                }else{
                    $total[$centervisit[$q]['rays_center_id']] =  $centervisit[$q]['rays_commission'];
                }
                    $centerid = $centervisit[$q]['rays_center_id'];
            }
            for ($i=0; $i <count($total) ; $i++) { 
               
            }
            foreach ($total as $key => $value) {
                $centername =RayCenter::where('id',$key)->select('center_name')->first();

                $all[$centername->center_name] = $value;
            }
            
        }

        
        return Response::json($all);

    }

    public function gettypevalue()
    {
        $type = $_POST['type'];
        if ($type == 'blogcategory') {
           $catblog = Blogcategory::where('published',1)->pluck('name','id');
           return Response::json($catblog);
        }else{
            $itemblog = Blogitem::where('published',1)->pluck('name','id');
            return Response::json($itemblog);
        }
        
    }

    public function uplo() {

        $input = Input::all();

        $rules = array(
            'file' => 'image|max:3000',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }

        //$destinationPath = base_path() . '/uploads/'; // upload path   
        $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension

        $fileName = rand(11111, 99999) . '.' . $extension; // renameing image



       // $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path


            $path = base_path('uploads/gallery/source/' . $fileName);
            $resize200 = base_path('uploads/gallery/resize200/' . $fileName);
            $resize800 = base_path('uploads/gallery/resize800/' . $fileName);
               // $f->move($destinationPath, $fileName);

            $image = Image::make(Input::file('file')->getRealPath());
            $image->save($path);

            $arrayimage = list($width, $height) = getimagesize(Input::file('file')->getRealPath());
            $widthreal = $arrayimage['0'];
            $heightreal = $arrayimage['1'];
            
            $width200 = ($widthreal / $heightreal) * 200;
            $height200 = $width200 / ($widthreal / $heightreal);

            $img200 = Image::canvas($width200, $height200);
            $image200 = Image::make(Input::file('file')->getRealPath())->resize($width200, $height200, function ($c) {
                $c->aspectRatio();
            });
            $img200->insert($image200, 'center');
            $img200->save($resize200);
        
            $width800 = ($widthreal / $heightreal) * 800;
            $height800 = $width800 / ($widthreal / $heightreal);

            $img800 = Image::canvas($width800, $height800);
            $image800 = Image::make(Input::file('file')->getRealPath())->resize($width800, $height800, function ($c) {
                $c->aspectRatio();
            });
            $img800->insert($image800, 'center');
            $img800->save($resize800);

        $add = new Gallery();
        $add->image = $fileName;
        $add->published = 1;
        $add->save();
    }

    public function printprescription()
{
    $about = Prescription::first();

    $diag_vis = $_POST['diag_vis'];
    $med_vis = $_POST['medids'];
    $med_per = $_POST['per'];
    if ($med_vis != '') {
        for ($i=0; $i <count($med_vis) ; $i++) { 
            $v = Medicine::where('id',$med_vis[$i])->select('med_name','med_dose')->first();
            $med[$v->med_name] = $med_per[$i];
        }
    }else{
        $med = [];
    }
    
    $anal_vis = $_POST['anal_vis'];
    if ($anal_vis != '') {
        for ($j=0; $j <count($anal_vis) ; $j++) { 
            $v = Analysis::where('id',$anal_vis[$j])->select('anal_name')->first();
            $anal[] = $v->anal_name;
        }
    }else{
        $anal = [];
    }
    
    $anal_cat = $_POST['anal_cat'];
    if ($anal_cat != '') {
        for ($o=0; $o <count($anal_cat) ; $o++) { 
            $v = Category::where('id',$anal_cat[$o])->select('name')->first();
            $cat[] = $v->name;
        }
    }else{
        $cat = [];
    }
    $ray_vis = $_POST['ray_vis'];
     if ($ray_vis != '') {
        for ($p=0; $p <count($ray_vis) ; $p++) { 
            $v = Ray::where('id',$ray_vis[$p])->select('rays_name')->first();
            $ray[] = $v->rays_name;
        }
    }else{
        $ray = [];
    }
    
    $patientname = $_POST['patientname'];
    $age = $_POST['age'];
    $mydata = [
        'name_ar' => $about->name_ar,
        'name_en' => $about->name_en,
        'address' => $about->address,
        'about_ar' => $about->about_ar,
        'about_en' => $about->about_en,
        'notes' => $about->notes,
        'patientname' => $patientname,
        'age' => $age,
    ];
    $html = view('print',['dataloop'=>$mydata,'med'=>$med,'anal'=>$anal,'cat'=>$cat,'ray'=>$ray])->render();
    
return $html;
    
}
public function deleteallimagepatient($ids) {
        $ids = explode(',', $ids);

        foreach ($ids as $id) {
            
            $p = Imagepatient::findOrFail($id);
            $img_path = base_path() . '/uploads/medicalpatient/source/';
            $img_path200 = base_path() . '/uploads/medicalpatient/small/';
            $img_path800 = base_path() . '/uploads/medicalpatient/large/';

            unlink(sprintf($img_path . '%s', $p->image));
            unlink(sprintf($img_path200 . '%s', $p->image));
            unlink(sprintf($img_path800 . '%s', $p->image));

            $p->delete();
        }
    }

    public function deleteoneimagepatient($id) {

        $p = Imagepatient::findOrFail($id);
        $img_path = base_path() . '/uploads/medicalpatient/source/';
        $img_path200 = base_path() . '/uploads/medicalpatient/small/';
        $img_path800 = base_path() . '/uploads/medicalpatient/large/';

        unlink(sprintf($img_path . '%s', $p->image));
        unlink(sprintf($img_path200 . '%s', $p->image));
        unlink(sprintf($img_path800 . '%s', $p->image));

        $p->delete();
    }
    public function orders()
    {
        
        $material = DB::table('materials')->lists('name','id');
        $supp = DB::table('suppliers')->lists('name','id');
        $o = Order::orderBy('id','desc')->get();
       return view('medical.order.order',compact('material','supp','o'));
    }
    public function sendorder()
    {
        $material = $_POST['material'];
        $supplier =$_POST['supplier']; 
        $quantity = $_POST['quantity'];
        if ($material != '' && $supplier != '' && $quantity != '') {
                $add = new Order();
                $add->material_id = $material;
                $add->supplier_id = $supplier;
                $add->quantity = $quantity;
                if($add->save()){
                   $supp =  Supplier::where('id',$add->supplier_id)->first();
                    if ($supp != null) {
                            $data = array(
                                'name' => $supp->name,
                                'phone' => $supp->phone,
                                'email' => $supp->email,
                            );
                            $emailsupp = $supp->email;
                            Mail::send('auth.emails.neworder', $data, function ($message) use ($emailsupp){

                             $message->to($emailsupp)->subject('New Order ');
                            }); 
                    }
                $s = 'Hey, Your Order send success';

                }else{
                    $s = 'Hey, Your Order not send try agian !';
                }
        }else{
            $s = 'Hey,Check Your fields and try agian !';
        }
       
        return Response::json($s);
    }
    public function deleteorder()
    {
        $ids = $_POST['id'];
        for ($i=0; $i <count($ids) ; $i++) { 
            $d = Order::findOrFail($ids[$i]);
            $d->delete();
        }
    }
    public function getsupvalue()
    {
        $sup = $_POST['sup'];
        $supp =  Supplier::where('id',$sup)->first();
        return Response::json($supp);
    }
    public function getavailablequantity()
    {
        $availablequantity = $_POST['material'];
        $vm = Material::where('id',$availablequantity)->select('all_quantity')->first();
        return Response::json($vm);
    }
     public function gettotalservices()
    {
       
      $pay = $_POST['pay'];
      $serid = str_replace("service_id%5B%5D=", "", $_POST['serid']);
      $vv= explode('&',$serid);
      $ids = [];
      foreach ($vv as $value) {
          $ids[] = $value;
      }

      $ser = DB::table('services') ->whereIn('id', $ids)->select(DB::raw('sum(price) AS total_services'))->first();
      $total = $ser->total_services + $pay ;
      return Response::json($total);
    }
}
