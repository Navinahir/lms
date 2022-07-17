<?php

class Admin_permission {

    function initialize() {
        $CI = & get_instance();
        $admin_role = $CI->session->userdata('user_role');
        $user_role = $CI->session->userdata('u_user_role');
        $vendor_role = $CI->session->userdata('v_user_role');
        $directory = $CI->router->fetch_directory();
        $controller = $CI->router->fetch_class();
        $action = $CI->router->fetch_method();
        if ($directory == 'admin/') {
            if (empty($admin_role) && ($controller == 'Mobile_WS' || $controller == 'Crone')) {
                
            } else if (empty($admin_role) && ($controller != 'login')) {
                $redirect = site_url(uri_string());
                redirect('admin/login?redirect=' . base64_encode($redirect));
            } else {
                if (!empty($admin_role) && ($controller == 'login' && $action == 'index')) {
                    redirect('admin/dashboard');
                }
            }
        } else if ($directory == 'vendor/') {
            if (empty($vendor_role) && ($controller != 'login')) {
                $redirect = site_url(uri_string());
                redirect('vendor/login?redirect=' . base64_encode($redirect));
            } else {
                if (!empty($vendor_role) && ($controller == 'login' && $action == 'index')) {
                    redirect('vendor/home');
                }
            }
        } else if ($directory == '') {
            if (empty($user_role) && ($controller != 'home' && $action != 'index')) {
                $redirect = site_url(uri_string());
                redirect('/login?redirect=' . base64_encode($redirect));
            } else {
                if (!empty($user_role) && ($controller == 'home' && $action == 'login')) {
                    redirect('/dashboard');
                }
            }
        }
    }

}

?>