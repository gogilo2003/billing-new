<?php

use Illuminate\Support\Str;

if (!function_exists('fetch_notifications')) {
    function fetch_notifications()
    {
        $notifications = \App\Models\Notification::where('read', 0)->get();
        return $notifications;
    }
}

// dump(fetch_notifications());

if (!function_exists('clean_isdn')) {
    function clean_isdn($isdn)
    {
        $isdn = str_replace('-', '', $isdn);
        $isdn = str_replace('+2540', '', $isdn);
        $isdn = ltrim($isdn, '0');
        $isdn = trim($isdn);

        if (strlen($isdn) === 9) {
            $isdn = '+254' . $isdn;
        } elseif (strlen($isdn) === 12 && !Str::startsWith($isdn, '+')) {
            $isdn = '+' . $isdn;
        } elseif (strlen($isdn) === 11 && (Str::startsWith($isdn, '6') || Str::startsWith($isdn, '7') || Str::startsWith($isdn, '1'))) {
            $isdn = '+' . $isdn;
        }
        return $isdn;
    }
}

if (!function_exists('clean_isdns')) {
    function clean_isdns($csv)
    {
        $isdns = [];
        foreach (explode(',', str_replace(' ', '', str_replace('or', ',', strtolower($csv)))) as $isdn) {
            $isdns[] = clean_isdn($isdn);
        }

        return implode(',', $isdns);
    }
}

if (!function_exists('make_html_list')) {
    function make_html_list($data, $type = 'ol')
    {
        $list = '<' . $type . '>';
        foreach ($data as $key => $value) {
            $list .= '<li>' . $value . '</li>';
        }
        $list .= '</' . $type . '>';

        return $list;
    }
}


if (!function_exists('is_current_path')) {
    function is_current_path($path, $string = false)
    {

        // print request()->url();
        if (request()->url() == $path) {
            if ($string) {
                return "active";
            }
            return true;
        }

        $path = ltrim(str_replace(url("/"), "", $path), '/');

        // print($path);

        if (request()->is($path)) {
            if ($string) {
                return "active";
            }
            return true;
        }

        if ($string) {
            return "";
        }
        return false;
    }
}


if (!function_exists('pdf_download')) {
    function pdf_download($view, $data = array(), $name = "document")
    {

        $pdf = App::make('snappy.pdf.wrapper');

        // return view('invoices.delivery',$data);

        $pdf->loadView($view, $data)
            ->setOption('no-outline', true)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0);
        // ->setOption('header-html', public_path('pdf/header.html'))
        // ->setOption('footer-html', public_path('pdf/footer.html'));
        return $pdf->download($name . '.pdf');
    }
}
