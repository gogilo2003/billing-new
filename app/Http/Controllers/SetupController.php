<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class SetupController extends Controller
{
    public function getSetup()
    {
        return view('setup.index');
    }

    function postMigrate(Request $request){
        $validator = Validator::make($request->all(),[
            'key'=>'required',
        ]);

        if($validator->fails()){
            return response('Page Not found',404);
        }
        if($setupkey = config('setup.key')){
            $output = null;
            if(Hash::check($request->key,$setupkey)){
                if (!defined('STDIN')) {
                    define('STDIN',fopen("php://stdin","r"));
                }
                Artisan::call('migrate',[
                    '--step'=>true,
                    '--force'=> true
                ]);
                $output['migration'] = Artisan::output();

                return response()->json($output);
            }else{
                return response()->json(['migration'=>'Unauthenticated']);
            }
        }else{
            if($request->key){
                $fp = fopen(config_path('setup.php') , 'w');
                fwrite($fp, '<?php return ' . var_export(['key'=>Hash::make($request->key)], true) . ';');
                fclose($fp);
            }
        }
    }
}
