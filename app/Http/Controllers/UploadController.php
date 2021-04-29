<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\File;
use FilesystemIterator;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // echo '<pre>';print_r(auth()->user()->id);echo '</pre>';
        return view('file-upload');
    }

    public function fileUpload(Request $req)
    {
        $userId= $req->userId;
        $base64_string= array();
        if($req->hasfile('filenames'))
        {
            foreach($req->file('filenames') as $file)
            {
                $base64_string[] = base64_encode(file_get_contents($file));;  
            }
        }

        // echo '<pre>';print_r($base64_string);echo '</pre>';die();

        $base64_string= implode(',',$base64_string);

        $folder = env('uploadFolder').'/'.$userId;
        // echo '<pre>';print_r($image_name);echo '</pre>';die();
        if (!file_exists($folder)) {
            if (!mkdir($folder, 0777, true)) {
                $m = array('msg' => "REJECTED, cant create folder");
                echo json_encode($m);
                return;
            }
        }

        $data = explode(',', $base64_string);
        foreach ($data as $key => $d){
            // $fullName = $folder."\\X_".$key."_". date("YmdHis") .".png"; // windows pake \\
            $fullName = $folder."/X_".$key."_". date("YmdHis") .".png"; // linux pake /
            $ifp = fopen($fullName, "wb");
            fwrite($ifp, base64_decode($data[1]));
            fclose($ifp);
            if (!$ifp) {
                $m = array('masg' => "REJECTED, ".$fullName."not saved" );
                echo json_encode($m);
                return;
            }
            $command = escapeshellcmd("python checkFace.py".$fullName);
            $output = shell_exec($command);
        }

        return back()->with('success', 'Data Your files has been successfully added');

    }

    public function laravelFileUpload(Request $req)
    {
        $req->validate([
            'file' => 'required|mimes:jpg,png|max:2048'
        ]);

        $userId= $req->userId;
        $fileModel = new File();
        if($req->file()) {
            $fileName = time().'_'.$req->file->getClientOriginalName();
            $filePath = $req->file('file')->storeAs($userId, $fileName, 'uploadFolder');

            $fileModel->name = time().'_'.$req->file->getClientOriginalName();
            $fileModel->file_path = env('uploadFolder') . $filePath;
            $fileModel->save();

            return back()
            ->with('success','File has been uploaded.')
            ->with('file', $fileName);
        }
    }

}