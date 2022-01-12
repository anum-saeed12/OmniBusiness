<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#Models
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('id', 'DESC')->paginate($this->count);
        $data = [
            'title'   => 'View Settings',
            'user'    => Auth::user(),
            'settings' => $settings,
        ];
        return view('admin.setting.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add Setting',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user()
        ];
        return view('admin.setting.add', $data);
    }

    public function edit($id)
    {
        $setting = Setting::find($id);
        $data = [
            'title'    => 'Update Setting',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'setting'  => $setting
        ];
        return view('admin.setting.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'setting'  => 'required|unique:App\Models\Setting',
            'value'    => 'required',
        ]);
        $exist = Setting::where('setting',$request->setting)->first();
       if($exist)
       {
           return redirect(
               route('setting.list.admin')
           )->with('success', 'Setting already exists!');
       }
        $data            = $request->all();
        $setting         = new Setting($data);
        $setting->save();

        return redirect(
            route('setting.list.admin')
        )->with('success', 'Setting was added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $exist=Setting::where('id',$id)->first();

        $request->validate([
            'setting'  => "required|unique:App\Models\Setting,setting,{$id}",
            'value'    => 'required',
        ]);

        $request->input('setting')   && $exist->setting   = $request->input('setting');
        $request->input('value')     && $exist->value     = $request->input('value');
        $exist->save();

        return redirect(
            route('setting.list.admin')
        )->with('success', 'Setting was updated successfully!');

    }
    /**
     * Remove the specified resource from storage.
     *
     */
    public function delete($id)
    {
        Setting::find($id)->delete();
        return redirect(
            route('setting.list.admin')
        )->with('success', 'Setting was deleted successfully!');
    }

}
