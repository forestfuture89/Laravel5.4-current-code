<?php

namespace App\Http\Controllers\Common;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function download($id)
    {
        $document = Document::find($id);
        $path = $document->path;
        return response()->download(storage_path('app/'.$path));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $this->validate($request, [
        'docId' => 'required|integer'
      ]);
      $document = Document::find($request->docId);
      $toDelete = $document;
      if ($toDelete != null) {
        $path = storage_path() . '/app/' . $toDelete->path;
        if (File::exists($path)) {
          File::delete($path);
        }
        $toDelete->delete();
        $response = Response::make('Ok', 200);
      } else {
        $response = Response::make('No Document Destroyed', 200);
      }
    }
}
