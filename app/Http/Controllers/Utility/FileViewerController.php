<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\Rent;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FileViewerController extends Controller
{
    public function viewFile($filePath)
    {
        $filePath = decrypt($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return view('template.file-viewer', [
            'fileType' => $extension,
            'filePath' => $filePath,
        ]);
    }
}
