<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once "./vendor/dompdf/dompdf/src/Autoloader.php";
use Dompdf\Dompdf;

class Pdfgenerator
{

    public function generate($html, $filename = '', $stream = true, $paper = 'A4', $orientation = "portrait")
    {
        $dompdf = new DOMPDF(array('enable_remote' => true));
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename . ".pdf", array("Attachment" => 0));
        } else {
            return $dompdf->output();
        }
    }
}
