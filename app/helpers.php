<?php

if (! function_exists('flash')) {
    function flash($title = 'Success!', $description = 'Yay! :)', $status = 'success')
    {
        session()->flash('title', $title);
        session()->flash('description', $description);
        session()->flash('status', $status);
    }
}