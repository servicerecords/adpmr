<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendingDocumentationRequest;
use App\Models\Application;
use App\Models\Constant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use ImagickException;
use Ramsey\Uuid\Uuid;

class SendingDocumentationController extends Controller
{
    const MAX_FILESIZE = 2000000;
    
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function index()
    {
        return view('sending-documentation');
    }
    
    /**
     * @param SendingDocumentationRequest $request
     * @return RedirectResponse
     * @throws ImagickException
     */
    public function save(SendingDocumentationRequest $request)
    {
        $storage = Config::get('filesystems.disks.s3.bucket', false) ? 's3' : 'local';
        
        Storage::disk($storage)->delete(session('death-certificate'));
        session()->forget('death-certificate');
        session()->forget('death-certificate-link');
        
        $filename = Uuid::uuid4();
        $extension = $request->file('death-certificate')->extension();
        $fileData = $request->file('death-certificate')->get();
        $fullPath = 'death-certificates/' . $filename . '.' . $extension;
        
        Storage::disk($storage)->put($fullPath, $fileData);
        
        session(['death-certificate' => $fullPath]);
        session(['death-certificate-link' => Storage::disk($storage)->url($fullPath)]);
        
        Application::getInstance()->markSectionComplete(Constant::SECTION_DEATH_CERTIFICATE);
        
        if (Application::getInstance()->sectionComplete(Constant::SECTION_CHECK_ANSWERS)) {
            return redirect()->route('check-answers');
        }
        
        return redirect()->route('applicant-details');
    }
}
