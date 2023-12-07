<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocFile;
use Illuminate\Support\Facades\Storage;

class DocFileController extends Controller
{

	// public function __construct()
	// {
    //     $this->middleware('auth');
	// }

    public function filter($params) {
        $params = json_decode( $params , true ) ;
        return DocFile::where('class',$params["class"])->where('link_id',$params["link_id"])->get();
    }

    public function fileupload(Request $request)
    {
        $execClass = $request->execClass;
        $link_id = $request->link_id;
        $delIds = $request->delIds;
        $files = request()->file('files');
        $no = 0;
        if ($files!="") {
            foreach ($files as $index => $image) {
                $path = Storage::disk("public")->put(null, $image);
                DocFile::insert([
                    'class' => $execClass,
                    'no' => ++$no,
                    'link_id' => $link_id,
                    'path' => "/storage/$path",
                    'name' => $path,
                ]);
            }
        }
        if ($delIds!="") {
            $ids = explode(',', $delIds);
            foreach (DocFile::whereIn('id',$ids)->get() as $dels) {
                Storage::disk("public")->delete($dels->name);
            }
            DocFile::whereIn('id', $ids)->delete();
        }
        return ;
    }
}
