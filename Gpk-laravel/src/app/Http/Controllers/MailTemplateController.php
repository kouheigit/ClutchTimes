<?php

namespace App\Http\Controllers;

use App\Models\MailTemplate;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    /**
     * メールテンプレート一覧表示
     */
    public function index(Request $request)
    {
        $query = MailTemplate::query();
        
        // タイプフィルター
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // ステータスフィルター
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $mail_templates = $query->orderBy('type', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('mail-templates.index', compact('mail_templates'));
    }
    
    /**
     * メールテンプレート詳細表示
     */
    public function show(MailTemplate $mailTemplate)
    {
        return view('mail-templates.show', compact('mailTemplate'));
    }
}

