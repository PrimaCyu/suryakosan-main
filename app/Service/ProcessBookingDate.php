<?php

namespace App\Service;

use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProcessBookingDate
{
    public function calculateBookingRange($product_kamar_kosan ,Request $request){

        $startDateTimeString = $request->start_date . ' ' . $request->start_time;
        $startDate = Carbon::parse($startDateTimeString);

        $endDate = $startDate->copy()
                    ->addYears((int) $request->input('tahun', 0))
                    ->addMonths((int) $request->input('bulan', 0))
                    ->addWeeks((int) $request->input('minggu', 0))
                    ->addDays((int) $request->input('hari', 0))
                    ->addHours((int) $request->input('jam', 0));

        $isBooked = Tamu::where([
                                    'product_kamar_kosan_id' => $product_kamar_kosan,
                                    'status' => 'pending'
                                ])->get();
        $failedBooking = false;

        foreach ($isBooked as $data) {
            $dbStart = Carbon::parse($data->start_date)->toDateTimeString();
            $dbEnd   = Carbon::parse($data->end_date)->toDateTimeString();

            if ($dbStart <= $endDate->toDateTimeString() || $dbEnd >= $startDate->toDateTimeString()) {
                $failedBooking = true;
                break;
            }
        }




        if($failedBooking){
            return [
                'status' => false,
                'message' => 'Tanggal Sudah di booking'
            ];
        }else{
            return [
                'status' => true,
                'start' => $startDate->toDateTimeString(),
                'end'   => $endDate->toDateTimeString()
            ];
        }

    }
}
