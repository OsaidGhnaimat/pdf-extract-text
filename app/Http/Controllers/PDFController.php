<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PDFController extends Controller
{
    public function ocr(Request $request)
{
    // Assuming 'pdf' is the name of the input field where the file is uploaded
    $uploadedFile = $request->file('pdf');

    // Upload the file to PDF.co
    $uploadResponse = Http::withHeaders([
        'x-api-key' => 'osaid.ghnaimat@gmail.com_S3pL02f9fal6HTr0K7S47SQkXs8fb7paw6ZLom9tziAom31eOP56THRA2GXGHBe1',
    ])->attach(
        'file',
        file_get_contents($uploadedFile),
        $uploadedFile->getClientOriginalName()
    )->post('https://api.pdf.co/v1/file/upload');

    // Check if file upload was successful
    if ($uploadResponse->successful()) {
        $fileUrl = $uploadResponse['url'];

        // Call the OCR API with the uploaded file URL
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => 'osaid.ghnaimat@gmail.com_S3pL02f9fal6HTr0K7S47SQkXs8fb7paw6ZLom9tziAom31eOP56THRA2GXGHBe1',
        ])->post('https://api.pdf.co/v1/pdf/convert/to/json2', [
            'url' => $fileUrl,
            'inline' => true,
            'async' => false,
        ]);

        // Handle the response from the OCR API
        return $response->json();
    } else {
        // Handle the case when file upload fails
        return response()->json(['error' => true, 'message' => 'Failed to upload file to PDF.co'], $uploadResponse->status());
    }
}



}
