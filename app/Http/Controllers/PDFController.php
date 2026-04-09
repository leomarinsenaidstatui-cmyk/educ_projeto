<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generate(){
    $data = array ("name" => "Leonardo Marin Vieira");   
    $pdf = Pdf::loadView("pdf.invoice", $data);
    }
}
oi