<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Service\ProcessBookingDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TamuBookingController extends Controller
{
    protected $processBookingDates;

    public function __construct(ProcessBookingDate $processBookingDates)
    {
        $this->processBookingDates = $processBookingDates;
    }

    public function booking($product_kamar_kosan, Request $request){
        $request->validate([
            'name'              => 'required|string|max:255',
            'telp'              => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'start_date'        => 'required|date',
            'start_time'        => 'required',
            'payment_method'    => 'required|string',
            'proof_of_transfer' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);


        $bookingDate = $this->processBookingDates->calculateBookingRange($product_kamar_kosan, $request);

        if(isset($bookingDate['status']) && $bookingDate['status'] == false){
            return back()->with('failed', $bookingDate['message']);
        }

        DB::transaction(function () use($product_kamar_kosan, $bookingDate, $request) {

            $data = [
                'product_kamar_kosan_id' => $product_kamar_kosan,
                'name'           => $request->name,
                'telp'           => $request->telp,
                'email'          => $request->email,
                'start_time'     => $request->start_time,
                'start_date'     => $bookingDate['start'],
                'end_date'       => $bookingDate['end'],
                'payment_method' => $request->payment_method,
                'total_price'    => $request->total_price,
            ];

            if($request->hasFile('proof_of_transfer')){
                $path = $request->file('proof_of_transfer')->store('tamu/proof-of-transfer', 'public');
                $data['proof_of_transfer'] = $path;
            }

            $tamu = Tamu::create($data);

        });

        // return response()->json([
        //     'message' => 'success booking',
        //     'data'    => $tamu
        // ]);

        return to_route('home')->with('success','Booking Success');
    }


}
