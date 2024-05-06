<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\AccessCode;
use App\Models\StockHistory;



class ScanController extends Controller
{
    public function scanQR(Request $request)
    {
        $qrData = $request->input('qrCode');

        $isValid = $this->validateQR($qrData);

        if ($isValid) {
            return response()->json(['success' => true, 'message' => 'QR kód je platný.', 'data' => $qrData]);
        } else {
            return response()->json(['success' => false, 'message' => 'QR kód není platný.']);
        }
    }

    public function showScanPage()
    {
        return view('/fullViews/shop/scanQr');
    }


    public function verifyQrCode(Request $request)
    {
        $qrContent = $request->input('qrContent'); 
        $accessCode = AccessCode::where('qr_content', $qrContent)->where('active', true)->first();
    
        if ($accessCode) {
            $this->logActivity('scan_qr', [
                'qr_code' => $accessCode->id ,
                'store_id' => $accessCode->store_id,
                'access_code' => $accessCode->code
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'QR kód je platný.',
                'accessCode' => $accessCode->code,
                'store_id' => $accessCode->store_id

            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Neplatný QR kód.']);
    }

    protected function logActivity($action, $details = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'details' => json_encode($details)
        ]);
    }

    public function logUserActivity(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string', 
            'details' => 'nullable'
        ]);
    
        $this->logActivity($validated['action'], $validated['details']);
        return response()->json(['success' => true]);
    }




}
