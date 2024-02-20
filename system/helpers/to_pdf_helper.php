<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Try increasing memory available, mostly for PDF generation
 */
ini_set("memory_limit","64M");
use Dompdf\Dompdf;
use Dompdf\Options;

function pdf_create($html, $filename, $stream=TRUE) 
{
    //$tempFileName = "./invoices_temp/" . uniqid() . ".html";
    //write_file($tempFileName, $html);
    // Composer's auto-loading functionality
    require __DIR__ . "/dompdf-2.0.3/autoload.inc.php";
    
    $contxt = stream_context_create([
            'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed'=> TRUE
            ]
    ]);
	
    $pdfOptions = new Options();
    $pdfOptions->set('isRemoteEnabled', true);
    $pdfOptions->set('isHtml5ParserEnabled', true);
    $pdfOptions->set("a4", "portrait");
    $pdfOptions->setChroot(getcwd());
    $pdfOptions->setIsRemoteEnabled(true);

    $dompdf = new Dompdf($pdfOptions);
    $dompdf->setHttpContext($contxt);
    $dompdf->set_paper("a4", "portrait");
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename);
    }
    write_file("./invoices_temp/$filename", $dompdf->output());
}
?>