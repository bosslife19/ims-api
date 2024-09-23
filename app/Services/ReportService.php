<?php

namespace App\Services;

use Dompdf\Dompdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class ReportService
{
    public static function GeneratePDF($items): ?string
    {
        $dompdf = new Dompdf();
        $html = self::generateHTML($items);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return $dompdf->output();
    }

    public static function GenerateExcel($items): ?string
    {
         // Create an export instance with the items
         $export = new ItemsExport($items);
        
         // Define the file path
         $filePath = 'reports/report.xlsx';
         
         // Store the Excel file in the public disk
         Excel::store($export, $filePath, 'public');
         
         // Return the file path
         return $filePath;
    }

    public static function GenerateDOCX($items): string
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        foreach ($items as $item) {
            $section->addText("Item: {$item->name}, Category: {$item->category}, Stock Level: {$item->quantity}");
        }
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $filePath = storage_path('app/reports/report.docx');
        $writer->save($filePath);
        return $filePath;
    }

    private static function generateHTML($items): string
    {
        $html = "<h1>Inventory Report</h1>";
        $html .= "<table border='1' cellpadding='10' cellspacing='0'>";
        $html .= "<thead><tr><th>Name</th><th>Category</th><th>Stock Level</th></tr></thead><tbody>";
        foreach ($items as $item) {
            $html .= "<tr><td>{$item->name}</td><td>{$item->category}</td><td>{$item->quantity}</td></tr>";
        }
        $html .= "</tbody></table>";
        return $html;
    }
}