<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportCsvUtil
{
    public function export(string $filename, array $headers, array $data, string $separator): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($headers, $separator, $data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $headers, $separator);

            foreach ($data as $row) {
                fputcsv($handle, $row, $separator);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '.csv"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('File-Download-Name', $filename . '.csv');

        return $response;
    }
}
