<?php

class json_api_authorize_controller
{
    public function get_user_id()
    {
        global $json_api;
        $email = $json_api->query->email;
        $pass = $json_api->query->password;
        $user = get_user_by('email', $email);
        if (wp_check_password($pass, $user->data->user_pass, $user->ID)) {
            return array(
                    "message" => $user->ID,
                );
        } else {
            return array(
                "message" => "Error: No user found",
            );
        }
    }
}
