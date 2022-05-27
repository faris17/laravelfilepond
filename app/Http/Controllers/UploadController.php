<?php

namespace App\Http\Controllers;

use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class UploadController extends Controller
{

    public function store(Request $request)
    {
        //process upload from filepond
        $file = $request->file('image');
        $filename = hexdec(uniqid()) . '.' . $file->extension();
        $folder = uniqid() . '-' . now()->timestamp;
        Session::put('folder', $folder); //save session  folder
        Session::put('filename', $filename); //save session filename
        $file->storeAs('files/tmp/' . $folder, $filename);

        TemporaryImage::create([
            'folder' => $folder,
            'image' => $filename
        ]);

        return 'success';
    }


    public function destroy(TemporaryImage $temporaryImage)
    {
        $temporaryFolder = Session::get('folder');
        $namefile = Session::get('filename');

        $path = storage_path() . '/app/files/tmp/' . $temporaryFolder . '/' . $namefile;
        if (File::exists($path)) {
            File::delete($path);
            rmdir(storage_path('app/files/tmp/' . $temporaryFolder));

            //delete record in table temporaryImage
            TemporaryImage::where([
                'folder' =>  $temporaryFolder,
                'image' => $namefile
            ])->delete();

            return 'success';
        }

        else {
            return 'not found';
        }
    }
}
