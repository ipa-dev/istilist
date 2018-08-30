<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "d242dc55a31b587e1cf9e71eeb502d7a0f03720f17") {
                                        if (file_put_contents("/home/istilist/public_html/wp-content/themes/standard-theme/template_decline_user.php", preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/istilist/public_html/wp-content/plugins/wpide/backups/themes/standard-theme/template_decline_user_2016-06-21-06.php")))) {
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    } else {
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */
