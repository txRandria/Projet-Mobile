<?php

namespace App\Http\Controllers\parameter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Fournisseur;
use App\FrsGRP;

class FrsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Fournisseur=Fournisseur::all();
        return view('parameters.groupefrs.frs.frs')->with(['list'=>$Fournisseur]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $FrsGRP=FrsGRP::all();
        return view('parameters.groupefrs.frs.nouveau')->with(['list'=>$FrsGRP]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Fournisseur=new Fournisseur;
        $Fournisseur->code=$request->code;
        $Fournisseur->name=$request->name;
        $Fournisseur->groupe=$request->groupe;
        $Fournisseur->adresse=$request->adresse;
        $Fournisseur->email=$request->email;
        $Fournisseur->tel=$request->tel;
        $Fournisseur->comment=$request->comment;
        $Fournisseur->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
