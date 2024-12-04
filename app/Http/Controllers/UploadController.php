<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;

class UploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048' // PDF files only, up to 2MB
        ]);

        if ($request->file('pdf')->isValid()) {
            // Specify the path to the pdftotext executable
            $pdftotextPath = 'c:/Program Files/Git/mingw64/bin/pdftotext';

            // Get the real path to the uploaded PDF file
            $pdfPath = $request->file('pdf')->getRealPath();

            // Extract text using the specified path to pdftotext
            $text = Pdf::getText($pdfPath, $pdftotextPath);

            // Do something with the extracted text, like saving it to a file or returning it as a response
            return response()->json(['text' => $text]);
        }

        // Handle invalid file
        return response()->json(['error' => 'Invalid PDF file'], 400);
    }
}
