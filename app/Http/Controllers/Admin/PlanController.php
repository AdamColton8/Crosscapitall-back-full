<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.plan.index', [
            'plans' => Plan::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.plan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tariff = new Plan();
        $tariff->name = $request->get('name');
        $tariff->description = '';
        $tariff->deposit_min = $request->get('deposit_min');
        $tariff->deposit_max = $request->get('deposit_max');
        $tariff->term = $request->get('term');
        $tariff->percent = $request->get('percent');
        $tariff->save();

        return redirect()->route('plan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.plan.show', ['plan' => Plan::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.plan.edit', ['plan' => Plan::findOrFail($id)]);
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
        $this->validate($request, [
            'name' => 'required',
            'deposit_min' => 'required|integer|min:0',
            'deposit_max' => 'required|integer|min:0',
            'term' => 'required|integer|min:0',
            'percent' => 'required|integer|min:0',
        ]);

        $tariff = Plan::findOrFail($id);
        $tariff->name = $request->get('name');
        $tariff->description = '';
        $tariff->deposit_min = $request->get('deposit_min');
        $tariff->deposit_max = $request->get('deposit_max');
        $tariff->term = $request->get('term');
        $tariff->percent = $request->get('percent');
        $tariff->save();

        return redirect()->route('plan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Plan::destroy($id);

        return redirect()->route('plan.index');
    }
}
