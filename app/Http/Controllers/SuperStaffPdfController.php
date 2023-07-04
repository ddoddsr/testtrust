<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SuperStaffPdfController extends Controller
{
    public function generatePdf(string $filePath, $record)
    {
        // $supervisor = User::where('id' , $super)->first();
// dd($record);
        // $staff = $record->supervising();
        $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf('P','pt','Letter') ;
        // $fpdf->AddFont('Uni','','39335_UniversCondensed.php');
        
        // get todays date time
        $dateTime = 'Updated: ' . Carbon::now()->format('M d Y');
        $title = 'IHOPKC - Supervisor\'s List';
        $fpdf->SetTitle($title);

        $fpdf->SetAutoPageBreak(true,5);

        $leaderWidth = $fpdf->GetPageWidth() -35 ;
        $taglinePos =  - 20 ;
        $tagline = 'Fire on the Alter'; // 'WL = Worship Leader; A = Associate WL; PL = Prayer Leader; SL = Section Leader; TBD = To Be Determined; FC = Forerunner Church; EGS = Encounter God Service.';//env('FOOTER_STATEMENT','Set FOOTER_STATEMENT in .env');

        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell( $leaderWidth ,12, $title, 0, 1, 'C');
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->Cell( $leaderWidth ,30, $dateTime, 0, 1, 'C');

        $superInfoTop = 80;
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->Text(60, $superInfoTop,$record->first_name);
        $fpdf->Text(60, $superInfoTop + 20 ,$record->last_name);
        $fpdf->Text(60, $superInfoTop + 40 ,$record->email);
        // Table parameters
        $topOfList = 250;
        $fpdf->SetFont('Arial', '', 12);
        $lnNum = 1;
        foreach( User::where('supervisor_id', $record->id )->get() as $staffLine) {
            $fpdf->Text(60, ($lnNum  * 20) + $topOfList,  $staffLine->first_name);
            $fpdf->Text(110, ($lnNum  * 20) + $topOfList,  $staffLine->last_name);
            $fpdf->Text(160, ($lnNum  * 20) + $topOfList,  $staffLine->email);
            $lnNum++;
        }
        
       

        $fpdf->SetY( $taglinePos );
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->Cell( $leaderWidth, 0, $tagline, 0, 1, 'C');
        $fpdf->Output('F', $filePath);
        return "PDF Finished";     
    }


    //TEST
    public function generate(string  $filePath = 'storage/test.pdf')
    {
        logger("SuperStaffPdfController generate to: " . $filePath );
        // $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf ;
        $fpdf = new \Codedge\Fpdf\Fpdf\Fpdf('L','pt','Letter') ;
        $fpdf->SetTitle('Generate test function');
        $fpdf->SetAutoPageBreak(true,5);
        $fpdf->AddPage('L', 'Letter');
        $fpdf->SetFont('Courier', 'B', 24);
        // $fpdf->Cell(50, 25, 'Hello World!');
        $fpdf->Cell(50, 25, 'SuperStaffPdfController');
        $fpdf->Output('F', $filePath);
        return 'back from controller';
    }


}