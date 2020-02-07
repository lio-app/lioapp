<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoinType;
use Storage;

class CoinTypeResource extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Coin = CoinType::all();
        if($request->ajax()) {
            return $Coin;
        } else {
            return view('admin.cointype.index', compact('Coin'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cointype.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            //'address' => 'required|max:255',
            'symbol' => 'required|max:255',
        ]);

        try {

            $Coin = $request->all();

            /*if($request->hasFile('qr_code')) {

                //$Coin['qr_code'] = $request->qr_code->store('qrcode');
                $Coin['qr_code'] = asset('storage/'.$request->qr_code->store('qrcode'));
            
            }*/

            $Coin = CoinType::create($Coin);

            return back()->with('flash_success','Coin Type Saved Successfully');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CoinType  $CoinType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return CoinType::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CoinType  $CoinType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $Coin = CoinType::findOrFail($id);
            
            return view('admin.cointype.edit',compact('Coin'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CoinType  $CoinType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'name' => 'required|max:255',
            //'address' => 'required|max:255',
            'symbol' => 'required|max:255',
        ]);

        try {

            $Coin = CoinType::findOrFail($id);

            /*if($request->hasFile('qr_code')) {

                //$Coin['qr_code'] = $request->qr_code->store('qrcode');
                $Coin->qr_code = asset('storage/'.$request->qr_code->store('qrcode'));
            
            }*/

            $Coin->name = $request->name;
            $Coin->symbol = $request->symbol;
            //$Coin->address = $request->address;

            $Coin->save();

            return redirect()->route('admin.cointype.index')->with('flash_success', 'Coin Type Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CoinType  $CoinType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {
            CoinType::find($id)->delete();
            return back()->with('message', 'Coin Type deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        }
    }


    public function disableStatus($id)
    {
        
        try {

            
            $Coin = CoinType::findOrFail($id);

            $Coin->status = '0';
            
            $Coin->save();

            return back()->with('message', 'Updated successfully');

        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        }
    }

    public function enableStatus($id)
    {
        
        try {

            
            $Coin = CoinType::findOrFail($id);

            $Coin->status = '1';
            
            $Coin->save();

            return back()->with('message', 'Updated successfully');

        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Coin Type Not Found');
        }
    }

}
