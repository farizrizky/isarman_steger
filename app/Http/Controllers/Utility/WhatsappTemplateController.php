<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\User;
use Illuminate\Http\Request;

class WhatsappTemplateController extends Controller
{
    public function whatsappStartChat($whatsappNumber){
        $whatsappNumber = str_replace([' ', '-', '(', ')'], '', $whatsappNumber); // Menghapus spasi, tanda hubung, dan kurung
        if (substr($whatsappNumber, 0, 1) == '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }

        $url = "https://wa.me/$whatsappNumber";

        return redirect($url);

    }
    public function whatsappInvoiceRent($rentId){
        $rent = Rent::with('renter')->find($rentId);
        //Validasi id sewa
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }
        $whatsappNumber = $rent->renter->renter_phone;
        $whatsappNumber = str_replace([' ', '-', '(', ')'], '', $whatsappNumber); // Menghapus spasi, tanda hubung, dan kurung
        if (substr($whatsappNumber, 0, 1) == '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }
        $message = "Salam hormat,\n\nBerikut kami lampirkan rincian rencana penyewaan Scaffolding anda:\n";
        $message .= "Lihat Rincian Rencana Sewa disini: \n\n".url('/file/'.encrypt($rent->rent_invoice_photo))."\n\n";
        $message .= "Terima kasih atas kepercayaan anda kepada kami.\n\n";
        $message .= "Salam,\n";
        $message .= "*CV. ISARMAN STEGER BENGKULU*\n\n";
        $message .= "_Catatan: Harap melakukan pembayaran diawal ke Rek. BCA : 6557006763 a.n Isarman_\n\n";
        $url = "https://api.whatsapp.com/send?phone=$whatsappNumber&text=".urlencode($message);

        return redirect($url);
    }

    public function whatsappInvoiceRentReturn($rentId){
        $rent = Rent::with('renter', 'rentReturn')->find($rentId);
        //Validasi id sewa
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Detail Penyewaan Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }
        $whatsappNumber = $rent->renter_phone;
        $whatsappNumber = str_replace([' ', '-', '(', ')'], '', $whatsappNumber); // Menghapus spasi, tanda hubung, dan kurung

        if (substr($whatsappNumber, 0, 1) == '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }
        $message = "Salam hormat,\n\nBerikut kami lampirkan rincian biaya pengembalian sewa Scaffolding anda:\n";
        $message .= "Lihat Rincian Biaya Pengembalian Sewa disini: \n\n".url('/file/'.encrypt($rent->rentReturn->rent_return_invoice_photo))."\n\n";
        $message .= "Terima kasih atas kepercayaan anda kepada kami.\n\n";
        $message .= "Salam,\n";
        $message .= "*CV. ISARMAN STEGER BENGKULU*\n\n";
        $message .= "_Catatan: Harap melakukan pembayaran ke Rek. BCA : 6557006763 a.n Isarman_\n\n";
        $url = "https://api.whatsapp.com/send?phone=$whatsappNumber&text=".urlencode($message);

        return redirect($url);
    }

    public function whatsappRequestApprovingRent($userId, $rentId){
        $user = User::where(['id' => $userId, 'is_active' => 1]);
        if(!$user->exists()){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "User tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }

        $canApprove = $user->first()->can('approve_rent');
        if(!$canApprove){
            $sweetalert =  [
                'state' => "error",
                'title' => "Akses Ditolak",
                'message' => "Anda tidak memiliki akses untuk menyetujui penyewaan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }
        $whatsappNumber = $user->first()->phone;
        $whatsappNumber = str_replace([' ', '-', '(', ')'], '', $whatsappNumber); // Menghapus spasi, tanda hubung, dan kurung
        if (substr($whatsappNumber, 0, 1) == '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }
        $message = "Salam hormat,\n\nIzin mohon menyetujui penyewaan Scaffolding yang telah diajukan oleh penyewa a.n ".\App\Helpers\DataHelper::getDataRent($rentId)->renter->renter_name."\n";
        $message .= "Lihat Rincian Penyewaan disini: \n\n".url('/sewa/draft/detail/'.$rentId)."\n\n";
        $message .= "Terima kasih atas bantuannya.\n\n";
        $message .= "Salam,\n";
        $message .= "*Admin CV. ISARMAN STEGER BENGKULU*\n\n";
        $url = "https://api.whatsapp.com/send?phone=$whatsappNumber&text=".urlencode($message);
        return redirect($url);
    }

    public function whatsappRemainingDurationRent($rentId){
        $rent = Rent::with('renter')->find($rentId);
        //Validasi id sewa
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }
        $whatsappNumber = $rent->renter->renter_phone;
        $whatsappNumber = str_replace([' ', '-', '(', ')'], '', $whatsappNumber); // Menghapus spasi, tanda hubung, dan kurung

        if (substr($whatsappNumber, 0, 1) == '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }

        // Menghitung sisa durasi sewa
        $remainingDuration = \App\Helpers\DateHelper::daysDiffFromNow($rent->rent_end_date);

        if($remainingDuration > 0){
            $message = "Salam hormat,\n\nKami ingin mengingatkan anda bahwa penyewaan Scaffolding anda akan berakhir pada tanggal *".\App\Helpers\DateHelper::dateFormat($rent->rent_end_date)." (".$remainingDuration." hari lagi)*"."\n\n";
        }else if($remainingDuration == 0){
            $message = "Salam hormat,\n\nKami ingin mengingatkan anda bahwa hari ini adalah hari terakhir penyewaan Scaffolding anda.\n\n";
        }else{
            $message = "Salam hormat,\n\nKami ingin mengingatkan anda bahwa penyewaan Scaffolding anda telah berakhir pada tanggal *".\App\Helpers\DateHelper::dateFormat($rent->rent_end_date)." (".abs($remainingDuration)." hari yang lalu)*"."\n\n";
        }   
        
        $message .= "Mohon segera melakukan konfirmasi pengembalian atau perpanjangan sewa jika diperlukan.\n\n";
        $message .= "Terima kasih atas perhatian anda.\n\n";
        $message .= "Salam,\n";
        $message .= "*CV. ISARMAN STEGER BENGKULU*\n\n";
        $url = "https://api.whatsapp.com/send?phone=$whatsappNumber&text=".urlencode($message);

        return redirect($url);
    }

}
