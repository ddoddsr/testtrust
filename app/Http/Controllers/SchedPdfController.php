<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
// use App\Models\Schedule;
// use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SchedPdfController extends Controller
{

    public function generatePdf(string $filePath)
    {
        $setWithScheds = $this->setSchedule();
        // 
        $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf('L','pt','Letter') ;
        $fpdf->AddFont('Uni','','39335_UniversCondensed.php');
        
        // get todays date time
        $dateTime = 'Updated: ' . Carbon::now()->format('M d Y');
        $title = 'IHOPKC - GLOBAL PRAYER ROOM 24-HOUR SCHEDULE';
        $fpdf->SetTitle($title);

        $fpdf->SetAutoPageBreak(true,5);

        $leaderWidth = $fpdf->GetPageWidth() -35 ;
        $taglinePos =  - 20 ;
        $tagline = 'WL = Worship Leader; A = Associate WL; PL = Prayer Leader; SL = Section Leader; TBD = To Be Determined; FC = Forerunner Church; EGS = Encounter God Service.';//env('FOOTER_STATEMENT','Set FOOTER_STATEMENT in .env');

        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell( $leaderWidth ,12, $title, 0, 1, 'C');
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->Cell( $leaderWidth ,30, $dateTime, 0, 1, 'C');

        // Table parameters
        $top = 72;
        $left = 30;  // 
        $right = $fpdf->GetPageWidth() -35 ;  // right margin
        $leftKey = $left + 60; // Size of Col 1
        $leftSet = $leftKey + 30; // Size of Col 2
        $dayWidth = ($right - $leftSet ) / 7 ;
        // $bottom =  580 ;
        $topSets = $top  + 16 ;
        $setH = 40.5;
        $bottomSet = $topSets + (12 * $setH);

       
        $fpdf->SetDrawColor(0,0,0);
        $fpdf->SetLineWidth(.5);

        $fpdf->Line( $left, $top, $right, $top);
        // horizonal lines 
        for ($i=0; $i < 13; $i++) { 
            $y = $topSets + ($i * $setH );
            $fpdf->Line(  $left, $y,  $right, $y);
        }

        $fpdf->Line(  $left, $top,  $left, $bottomSet);
        $fpdf->Line(  $leftKey  , $top,  $leftKey , $bottomSet);
        // Top row DAYS OF WEEK
        $fpdf->SetXY( $left  , $top,);
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->Cell( 60, 16, "Day", '', 0, 'C');
        foreach (['Sunday', 'Monday', 'Tuesday', 
                'Wednesday', 'Thursdday', 
                'Friday', 'Saturday'
            ] as $columnIndex  => $dayOfWeek) {
            $fpdf->SetX( $leftSet + 2 + ($columnIndex  * $dayWidth ));
            $fpdf->Cell( $dayWidth, 16, $dayOfWeek, '', 0, 'C');
        }
        
        for ($i=0; $i < 8; $i++) { 
            $x = $leftSet + ($i * $dayWidth );
            $fpdf->Line(   $x, $top,   $x, $bottomSet);
        }
        // Columns 1 & 2
        $i = 0;
        $fpdf->SetFont('Arial', '', 8);
        foreach($setWithScheds as $set) {
            $fpdf->SetY( $topSets + 2 + ($i * $setH ));
            $fpdf->MultiCell( 60, 12 , $set['setOfDay'],0,'C');
            $fpdf->MultiCell( 60, 12 , $set['title'],0,'C');
            $fpdf->SetY( $topSets + 2 + ($i * $setH ));
            $fpdf->SetX($leftKey);
            $fpdf->MultiCell( 20, 10 , "WL A PL SL",0,'C');
            if ( $i++ == 11 ) break;
        }
        
        // SET TABLE


        $columnIndex = 0;
        $setIndex = 0;
        
        $fpdf->SetFont('Uni', '', 9.25);
        foreach($setWithScheds as $set) {

            $fpdf->Text(
                $leftSet + ($columnIndex  * $dayWidth ) + 4,
                $topSets + ($setIndex * $setH ) + 8.5,
                strlen($set['worshipLeader']) > 1 ?  $set['worshipLeader'] : 'TBD'
            );
            $fpdf->Text(
                $leftSet + ($columnIndex  * $dayWidth ) + 4,
                $topSets + ($setIndex * $setH )  + ($setH  * .25) + 8.3,
                'TBD',
                // strlen($set['associateWorshipLeader']) > 1 ?  $set['associateWorshipLeader'] : 'TBD'
            );
            $fpdf->Text(
                $leftSet + ($columnIndex  * $dayWidth ) + 4,
                $topSets + ($setIndex * $setH ) + ($setH  * .5)  + 8.15,
                // $set['prayerLeader']
                strlen($set['prayerLeader']) > 1 ?  $set['prayerLeader'] : 'TBD'
            );
            $fpdf->Text(
                $leftSet + ($columnIndex  * $dayWidth ) + 4,
                $topSets + ($setIndex * $setH )  + ($setH  * .75)  +8,
                // $set['sectionLeader']
                strlen($set['sectionLeader']) > 1 ? $set['sectionLeader'] : 'TBD'
            );
            
            //each 12sets start in column 1
            $setIndex++;
            if ($setIndex == 12 ) {
                $columnIndex++ ;
                $setIndex = 0;
            }

        }


        $fpdf->SetY( $taglinePos );
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->Cell( $leaderWidth, 0, $tagline, 0, 1, 'C');
        $fpdf->Output('F', $filePath);
        return;     
    }



    public function setSchedule($location='GPR')
    {
        // get staff sched into sets
        $setRecords = DB::table('sets')
        ->leftJoin('users as pluser', 'sets.prayer_leader_id', '=', 'pluser.id') 
        ->leftJoin('users as wluser', 'sets.worship_leader_id', '=', 'wluser.id') 
        ->leftJoin('users as sluser', 'sets.section_leader_id', '=', 'sluser.id') 
        ->select('sets.*',
            'pluser.first_name as pl_first_name',  'pluser.last_name as pl_last_name',
            'wluser.first_name as wl_first_name',  'wluser.last_name as wl_last_name',
            'sluser.first_name as sl_first_name',  'sluser.last_name as sl_last_name')
        ->get();
        $setWithScheds = [];

        foreach ($setRecords as $setData ) {
            // Get all  names for this set
            $setWithScheds[] = [
                'prayerLeader' => trim($setData->pl_first_name . ' ' . $setData->pl_last_name ),
                'worshipLeader' => trim($setData->wl_first_name . ' ' . $setData->wl_last_name ),
                'sectionLeader' => trim($setData->sl_first_name . ' ' . $setData->sl_last_name ), 
                'title' => $setData->title,
                'dayOfWeek' => $setData->dayOfWeek,
                'setOfDay' =>  $setData->setOfDay,
                'sequence' =>  $setData->sequence,
            ];
        }
        return $setWithScheds;
    }


    //TEST
    public function generate(string  $filePath)
    {
        logger("PdfController generate");
        $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf ;
            $fpdf->AddPage('L', 'Letter');
            $fpdf->SetFont('Courier', 'B', 24);
            $fpdf->Cell(50, 25, 'Hello World!');
            $fpdf->Output('F', $filePath);
        return;
    }
}