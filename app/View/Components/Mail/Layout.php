<?php

namespace App\View\Components\Mail;

use Illuminate\View\Component;

class Layout extends Component
{
    public function render()
    {
        return view('emails.layouts.master');
    }
}
