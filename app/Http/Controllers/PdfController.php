<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Set;
use App\Models\Schedule;
// use Illuminate\Http\Request;
// use Codedge\Fpdf\Facades\Fpdf;


class PdfController extends Controller
{

    public function generatePdf(string $filePath)
    {
        $setWithScheds = $this->setSchedule();

        $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf('L','pt','Letter') ;
        
        // get todays date time
        $dateTime = 'Updated: ' . Carbon::now()->format('M d Y');

        $fpdf->SetTitle('Sacred Trust');
        $fpdf->SetAutoPageBreak(true,5);

        $leaderWidth = $fpdf->GetPageWidth() -35 ;
        $taglinePos =  - 20 ;
        $tagline = env('FOOTER_STATEMENT','Set FOOTER_STATEMENT in .env');
        $topOfColumns = 80;
        $postionColumn = 0; // index of name column

        $namesPerColumn = 48;
        $maxColumns = 6;
        foreach($setWithScheds as $set) {
            $maxNamesOnPage = $namesPerColumn * $maxColumns ; // start with 6 columns
            if ( $maxNamesOnPage  < 22) {
                $nameFontSize = 10;
                $rowHeight = 12;
                $maxColumns = 6;
            } else {
                $nameFontSize = 8;
                $rowHeight = 10;
                $maxColumns = 7;
            }
            $columnSpacing = $fpdf->GetPageWidth() / $maxColumns -5;

            $fpdf->AddPage();
            $fpdf->SetFont('Arial', 'B', 24);
            $fpdf->Cell(0,0, $set['title'], 0, 1, 'C');
            $fpdf->Ln(32);
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->Cell($leaderWidth,0, "Worship Leader" .$set['worshipLeader']  , 0, 1, 'L');
            $fpdf->Cell($leaderWidth,0, "Prayer Leader" . $set['prayerLeader'], 0, 1, 'C');
            $fpdf->Cell($leaderWidth,0, "Section Leader" . $set['sectionLeader']  , 0, 1, 'R');
            $fpdf->Ln(32);
            $fpdf->SetFont('Arial', '', $nameFontSize);

            $rowCount = 0;
            $postionColumn = 0;

            foreach($set['scheds'] as $name ) {
                if ($name != '' ) {
                    $rowCount++;

                    $fpdf->Text( 50 +($columnSpacing * $postionColumn),
                        $topOfColumns + ( $rowCount * $rowHeight), $name );

                    if ($rowCount != 1 && $rowCount % $namesPerColumn == 0) {
                        $postionColumn++;
                    }
                    if( $namesPerColumn == $rowCount ) { $rowCount = 0; }
                }
            }

            $fpdf->SetFont('Arial', 'B', 12);
            $fpdf->SetY( $taglinePos );
            $fpdf->Cell( $leaderWidth, 0, $set['dayOfWeek'] . ' ' . $set['setOfDay'], 0, 1, 'L');
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->Cell( $leaderWidth, 0, $tagline, 0, 1, 'C');
            $fpdf->SetFont('Arial', 'B', 12);
            $fpdf->Cell( $leaderWidth ,0, $dateTime, 0, 1, 'R');
        }
        $fpdf->Output('F', $filePath);
        return;     
    }



    public function setSchedule($location='GPR')
    {
        // get staff sched into sets
        $setRecords = Set::all();
        $setWithScheds = [];

        foreach ($setRecords as $setData ) {
            // Get all  names for this set
            $setWithScheds[] = [
                'sequence' => $setData->sequence,
                'dayOfWeek' => $setData->dayOfWeek,
                'setOfDay' => $setData->setOfDay,
                'location' => $setData->location ,
                'sectionLeader' => $setData->sectionLeader ,
                'worshipLeader' => $setData->worshipLeader ,
                'prayerLeader' => $setData->prayerLeader ,
                'title' => $setData->title ,
                'scheds' =>  $this->collectSchedSets($setData->dayOfWeek, $setData->setOfDay, $location) ,
            ];
        }
        return $setWithScheds;
    }

    public function collectSchedSets($day, $set, $location) {
        $schedLines = [];

        foreach(Schedule::where('location', $location)
                       ->where('day' , $day)
                        ->get() as $schedule) {
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

        // check for duration >= 60 min
        if ($isStart && $isEnd && $schedDuration >= 60 ) {
            return (trim($schedule->user->first_name). ' ' . trim($schedule->user->lastn_ame)); 
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