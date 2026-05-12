<?php

namespace App\Http\Controllers;

use App\Models\ServiceOption;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceOptionController extends Controller
{
    /**
     * サービスオプション一覧表示
     */
    public function index(Request $request)
    {
        $service_id = $request->input('service_id');
        
        $query = ServiceOption::with('service');
        
        if ($service_id) {
            $query->where('service_id', $service_id);
        }
        
        $service_options = $query->orderBy('sort', 'asc')->get();
        
        return view('service-options.index', compact('service_options', 'service_id'));
    }
    
    /**
     * サービスオプション詳細表示
     */
    public function show(ServiceOption $serviceOption)
    {
        $serviceOption->load('service');
        
        return view('service-options.show', compact('serviceOption'));
    }
}

