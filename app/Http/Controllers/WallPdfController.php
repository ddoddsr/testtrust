<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WallPdfController extends Controller
{
    public $dateTime; 
    public function generatePdf(string $filePath, string $location = 'GPR')
    {
        $setWithScheds = $this->setSchedule($location);

        $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf('L','pt','Letter') ;
        
        // get todays date time
        $this->dateTime = 'Updated: ' . Carbon::now()->format('M d Y');

        $fpdf->SetTitle('Sacred Trust');
        $fpdf->SetAutoPageBreak(true,5);

        $leaderWidth = $fpdf->GetPageWidth() -35 ;
        $taglinePos =  - 20 ;
        $tagline = env('FOOTER_STATEMENT','Set FOOTER_STATEMENT in .env');
        $topOfColumns = 80;
        $postionColumn = 0; 

        foreach($setWithScheds as $set) {
            $schedCnt = count($set['scheds']);
            // Adlust schedCnt per page count
            if( $schedCnt < 180 ) {
                $nameFontSize = 12;
                $rowHeight = 16;
                $maxColumns = 6;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 7 ;
                $namesPerColumn = 30;
            } elseif ( $schedCnt < 210 ) {
                $nameFontSize = 10;
                $rowHeight = 14;
                $maxColumns = 7;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 5 ;
                $namesPerColumn = 30;
            } else {  // Up to 280 per page
                $nameFontSize = 8;
                $rowHeight = 12;
                $maxColumns = 7;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 5 ;
                $namesPerColumn = 40;
            }
            
            $fpdf->AddPage();
            $fpdf->SetFont('Arial', 'B', 24);
            $fpdf->Cell(0,0, $set['title'], 0, 1, 'C');
            $fpdf->Ln(32);
            $fpdf->SetFont('Arial', 'B', 10);
            
            if ($set['worshipLeader']) {
                $fpdf->Cell($leaderWidth,0, "Worship Leader: " .$set['worshipLeader']  , 0, 1, 'L');
            } 
            if ($set['prayerLeader']){
                $fpdf->Cell($leaderWidth,0, "Prayer Leader: " . $set['prayerLeader'], 0, 1, 'C');
            }
            if ($set['sectionLeader']) {
                $fpdf->Cell($leaderWidth,0, "Section Leader: " . $set['sectionLeader']  , 0, 1, 'R');
            }
            
            $fpdf->Ln(32);
            $fpdf->SetFont('Arial', '', $nameFontSize);
            
            $rowCount = 0;
            $postionColumn = 0;

            foreach($set['scheds'] as $name ) {
                if ($name != '' ) {
                    $rowCount++;
                    
                    $name = iconv('UTF-8', 'windows-1252', $name);

                    $fpdf->Text( 50 +($columnSpacing * $postionColumn),
                        $topOfColumns + ( $rowCount * $rowHeight), $name );

                    if ($rowCount != 1 && $rowCount % $namesPerColumn == 0) {
                        $postionColumn++;
                    }
                    if( $namesPerColumn == $rowCount ) { $rowCount = 0; }
                }
            }

            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetY( $taglinePos );
            $fpdf->Cell( $leaderWidth, 0, $set['dayOfWeek'] . ' ' . $set['setOfDay'], 0, 1, 'L');
            $fpdf->Cell( $leaderWidth, 0, $tagline, 0, 1, 'C');
            $fpdf->Cell( $leaderWidth ,0, $this->dateTime, 0, 1, 'R');
        }
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
        ->where('location',$location )
        ->get();
        $setWithScheds = [];

        foreach ($setRecords as $setData ) {
            // Get all  names for this set
            $setWithScheds[] = [
                'sequence' => $setData->sequence,
                'dayOfWeek' => $setData->dayOfWeek,
                'setOfDay' => $setData->setOfDay,
                'location' => $setData->location,
                'prayerLeader' => trim($setData->pl_first_name . ' ' . $setData->pl_last_name ),
                'worshipLeader' => trim($setData->wl_first_name . ' ' . $setData->wl_last_name ),
                'sectionLeader' => trim($setData->sl_first_name . ' ' . $setData->sl_last_name ),
                'title' => $setData->title,
                'scheds' =>  $this->collectSchedSets($setData->dayOfWeek, $setData->setOfDay, $location),
            ];
        }
        return $setWithScheds;
    }

    public function collectSchedSets($day, $set, $location) {
        $schedLines = [];

        foreach(Schedule::where('location', $location)
                ->leftJoin('users', 'users.id', '=', 'schedules.user_id')
                ->where('day' , $day)
                ->whereIn('users.designation_id', [1,2,5,7])
                ->get() as $schedule
            ) {
            $schedLines[] = $this->modSchedLines($set, $schedule);
        }

        asort($schedLines);
        return array_unique($schedLines);
    }
    
    public function modSchedLines($setTime, $schedule) {
        // If schedule has 60min or more in a set, the staff name is added to array
        $setTimeStartM = Carbon::parse($setTime)->addMinutes(60); //->format('h:i a');
        $setTimeEndM = Carbon::parse($setTime)->addMinutes(60); //->format('h:i a');

        $scheduleStartM = Carbon::parse($schedule->start); //->format('h:i a');
        $scheduleEndM = Carbon::parse($schedule->end); //->format('h:i a');

        $isStart = $scheduleStartM->lte($setTimeStartM );
        $isEnd   = $scheduleEndM->gte($setTimeEndM);

        $schedDuration = $scheduleStartM->diffInMinutes($schedule->end);
        // logger($schedule->user->exit_date );
        // logger( $this->dateTime);
        // check for duration >= 60 min
        if ($isStart && $isEnd 
        && $schedDuration >= 60
        && (  $schedule->user->exit_date == null
            ||
            Carbon::parse($schedule->user->exit_date) > Carbon::parse($this->dateTime)
            )
         ) {
            return Str::title((trim($schedule->user->first_name). ' ' . trim($schedule->user->last_name))); 
        } else {
            return ;
        }
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