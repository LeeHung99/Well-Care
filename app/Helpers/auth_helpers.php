<?php

if (!function_exists('is_admin')) {
    function is_admin() {
        return auth()->check() && auth()->user()->role == 1;
    }
}

if (!function_exists('is_editor')) {
    function is_editor() {
        return auth()->check() && in_array(auth()->user()->role, [1, 2]);
    }
}

if (!function_exists('is_post_editor')) {
    function is_post_editor() {
        return auth()->check() && in_array(auth()->user()->role, [1, 3]);
    }
}