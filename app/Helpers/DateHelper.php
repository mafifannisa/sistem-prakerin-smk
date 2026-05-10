<?php

if (!function_exists('tanggal_indonesia')) {
    function tanggal_indonesia($date = null)
    {
        $date = $date ?? \Carbon\Carbon::now();
        
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        
        $bulan = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];
        
        $hari_ini = $hari[$date->format('l')];
        $bulan_ini = $bulan[$date->format('F')];
        $tanggal = $date->format('j');
        $tahun = $date->format('Y');
        
        return $hari_ini . ', ' . $tanggal . ' ' . $bulan_ini . ' ' . $tahun;
    }
}