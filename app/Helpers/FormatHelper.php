<?php

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($number)
    {
        // Hapus karakter selain angka
        $clean = preg_replace('/[^0-9]/', '', $number);

        // Ubah 0xxx jadi +62xxx
        if (substr($clean, 0, 1) === '0') {
            $clean = '+62' . substr($clean, 1);
        } elseif (substr($clean, 0, 2) === '62') {
            $clean = '+' . $clean;
        }

        // Format menjadi +62-xxxx-xxxx-xxx
        return preg_replace('/^(\+62)(\d{3,4})(\d{3,4})(\d{0,4})$/', '$1-$2-$3-$4', $clean);
    }
}
