<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WallPdfController extends Controller
{
    public $dateTime;
    public function generatePdf(string $filePath, int $location = 1)
    {
        $setWithScheds = $this->setSchedule($location);
// dd($setWithScheds);
        $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf('L','pt','Letter') ;

        // get todays date time
        $this->dateTime = 'Updated: ' . Carbon::now()->format('M d Y');

        $fpdf->SetTitle('Sacred Trust');
        $fpdf->SetAutoPageBreak(true,5);

        $leaderWidth = $fpdf->GetPageWidth() -44 ; //-35 ;

        $leftMargin = 15; //6;
        $namesMargin = 25;
        $taglinePos =  - 20 ;   // vert from bottom
        $tagline = env('FOOTER_STATEMENT','Set FOOTER_STATEMENT in .env');
        $topOfColumns = 70;
        $postionColumn = 0;
        $sequence = 1;
        foreach($setWithScheds as $set) {
            $schedCnt = count($set['scheds']);
            // Adlust schedCnt per page count
            if( $schedCnt < 181 ) {
                $nameFontSize = 12;
                $rowHeight = 16;
                $maxColumns = 6;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 8 ;
                $namesPerColumn = 30;
            } elseif ( $schedCnt < 211 ) {
                $nameFontSize = 11;
                $rowHeight = 14.4;
                $maxColumns = 6;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 6 ;
                $namesPerColumn = 35;
            } elseif( $schedCnt < 241 ) {
                $nameFontSize = 9.5;
                $rowHeight = 12;
                $maxColumns = 6;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 6 ;
                $namesPerColumn = 40;
            } elseif( $schedCnt < 286 ) {
                $nameFontSize = 8.4;
                $rowHeight = 10;
                $maxColumns = 7;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 6 ;
                $namesPerColumn = 40;
            } else {  // > 350
                $nameFontSize = 5.5;
                $rowHeight = 8;
                $maxColumns = 8;
                $columnSpacing = ( $fpdf->GetPageWidth() / $maxColumns ) - 6 ;
                $namesPerColumn = 62;
            }
            // logger(['set' => [
            //     'sequencec' => $sequence,
            //     // $set['dayOfWeek'],
            //     // $set['setOfDay'],
            //     'cnt' => $schedCnt,
            //     'fontsz' => $nameFontSize,
            //     'rowHt' => $rowHeight,
            //     'colNum' => $maxColumns,
            //     'colSpace' => $columnSpacing,
            //     'NamePerCol' => $namesPerColumn ]]);
            $sequence++ ;
            $fpdf->AddPage();
            $fpdf->SetFont('Arial', 'B', 24);
            $fpdf->Cell(0,0, $set['title'], 0, 1, 'C');
            $fpdf->Ln(32);
            $fpdf->SetFont('Arial', '', 10);
            $fpdf->SetX( $leftMargin );
            if ($set['worshipLeader']) {
                $name = iconv('UTF-8', 'windows-1252', $set['worshipLeader']);
                $fpdf->Cell($leaderWidth,0, "Worship Leader: " . $name, 0, 1, 'L');
            }
            if ($set['prayerLeader']){
                $name = iconv('UTF-8', 'windows-1252', $set['prayerLeader']);
                $fpdf->Cell($leaderWidth,0, "Prayer Leader: " . $name, 0, 1, 'C');
            }
            if ($set['sectionLeader']) {
                $name = iconv('UTF-8', 'windows-1252', $set['sectionLeader']);
                $fpdf->Cell($leaderWidth,0, "Section Leader: " . $name, 0, 1, 'R');
            }

            $fpdf->Ln(32);
            $fpdf->SetFont('Arial', '', $nameFontSize);

            $rowCount = 0;
            $postionColumn = 0;

            foreach($set['scheds'] as $name ) {
                if ($name != '' ) {
                    $rowCount++;

                    $name = iconv('UTF-8', 'windows-1252', $name);
                    $columnSpacingNoMargin = $columnSpacing - $leftMargin ;
                    $getNameWidth = $fpdf->GetStringWidth($name);

                    $shrink = $getNameWidth / $columnSpacingNoMargin;
                    if($getNameWidth  > $columnSpacingNoMargin) {

                        // logger([$name => [
                        //     'strlen' => strlen($name),
                        //     'getStrWidth' => $getNameWidth,
                        //     'colSpacingNoMargin' => $columnSpacingNoMargin,

                        //     'tmpFontSize' => $nameFontSize / $shrink,
                        //     'tmpFontSizeDiv' => $nameFontSize / $shrink,
                        //     'stdFontSize' => $nameFontSize,
                        // ]]);


                        $tmpFontSize = $nameFontSize / $shrink;

                        $fpdf->SetFont('Arial', '', $tmpFontSize);
                        $fpdf->Text( $namesMargin +($columnSpacing * $postionColumn),
                        $topOfColumns + ( $rowCount * $rowHeight), $name );
                        $fpdf->SetFont('Arial', '', $nameFontSize);

                    } else {
                        $fpdf->Text( $namesMargin +($columnSpacing * $postionColumn),
                        $topOfColumns + ( $rowCount * $rowHeight), $name );
                    }

                    if ($rowCount != 1 && $rowCount % $namesPerColumn == 0) {
                        $postionColumn++;
                    }
                    if( $namesPerColumn == $rowCount ) { $rowCount = 0; }
                }
            }

            $fpdf->SetFont('Arial', '', 10);
            $fpdf->SetY( $taglinePos );
            $fpdf->Cell( $leaderWidth, 0, $set['dayOfWeek'] . ' ' . $set['setOfDay'], 0, 1, 'L');
            $fpdf->Cell( $leaderWidth, 0, $tagline, 0, 1, 'C');
            $fpdf->Cell( $leaderWidth ,0, $this->dateTime, 0, 1, 'R');
        }

        //$fpdf->Output('F', $filePath);
        return;
    }


    public function setSchedule($location)
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
        ->where('location_id', $location )
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
        logger('Day: ' . $day .' ' . $set.' ' . $location);

        $schedLines = [];
        foreach(Schedule::where('location_id', $location)
                ->join('users', 'users.id', '=', 'schedules.user_id')
                ->where('day' , $day)
                ->where('deleted_at' , null)
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
        // if these numbers change, put in a setup or env
        // 60 min after the beginning
        $setTimeStartM = Carbon::parse($setTime)->addMinutes(60); //->format('h:i a');
        /// 60 minuts before the ending
        $setTimeEndM = Carbon::parse($setTime)->addMinutes(60); //->format('h:i a');

        $scheduleStartM = Carbon::parse($schedule->start); //->format('h:i a');
        $scheduleEndM = Carbon::parse($schedule->end); //->format('h:i a');
        if ( substr( $scheduleEndM, 11) == '00:00:00') {
            logger(trim($schedule->user->first_name). ' ' . trim($schedule->user->last_name));
            logger( $this->dateTime);
            logger('E '. $scheduleEndM);
            $scheduleEndM->subSeconds(5);
            logger('E '. $scheduleEndM);
        }
        $isStart = $scheduleStartM->lte($setTimeStartM );
        $isEnd   = $scheduleEndM->gte($setTimeEndM);
        // if (Carbon::parse($schedule->end) == '00:00:00') {
        // if ($isStart
        //     && $setTime == '10pm'
        //     && substr( $scheduleEndM, 11) == '00:00:00')
        // {
        //     logger(trim($schedule->user->first_name). ' ' . trim($schedule->user->last_name));
        //     logger($setTime);
        //     logger(Carbon::parse($schedule->end)->format('h:i a'));
        //     logger($setTimeStartM);
        //     logger($setTimeEndM);
        //     logger($scheduleStartM);
        //     logger('E '. substr( $scheduleEndM, 11));
        //     // logger('E '. $scheduleEndM);
        //     logger( $this->dateTime);
        // }
        $schedDuration = $scheduleStartM->diffInMinutes($schedule->end);
        // logger($schedule->user->exit_date );
        // check for duration >= 60 min
        if ($isStart && $isEnd
            && $schedDuration >= 60
            && ( ($schedule->user && $schedule->user->exit_date == null )
                ||
                Carbon::parse($schedule->user->exit_date) > Carbon::parse($this->dateTime)
            )
         ) {
            // logger($setTime . ': ' .trim($schedule->user->first_name). ' ' . trim($schedule->user->last_name));
            return trim($schedule->user->first_name). ' ' . trim($schedule->user->last_name);
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
