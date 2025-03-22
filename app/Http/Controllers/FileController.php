<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getFile($fileName)
    {
        if (!Storage::exists($fileName)) {
            return abort(404);
        }

        $file = Storage::get($fileName);
        $type = Storage::mimeType($fileName);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Content-Disposition', 'inline; filename="' . basename($fileName) . '"');
    }
}
