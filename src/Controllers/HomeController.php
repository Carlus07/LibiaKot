<?php

namespace Controllers;
//require 'src\Controllers\Tools\Pdf.php';

class HomeController extends Controller {
	
    public function index() {

		/*$pdf = new \FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(40,10,'Hello World !');
		$pdf->Output();*/
        $this->render('home.index');
    }
}
