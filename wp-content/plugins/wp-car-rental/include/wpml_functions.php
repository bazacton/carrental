<?php

if (!function_exists('cs_wpml_lang_url')) {

    function cs_wpml_lang_url() {

        if (function_exists('icl_object_id')) {

            global $sitepress;

            $cs_server_uri = $_SERVER['REQUEST_URI'];
            $cs_server_uri = explode('/', $cs_server_uri);

            $cs_active_langs = $sitepress->get_active_languages();

            if (is_array($cs_active_langs) && sizeof($cs_active_langs) > 0) {
                foreach ($cs_server_uri as $uri) {

                    if (array_key_exists($uri, $cs_active_langs)) {
                        return $uri;
                    }
                }
            }
        }
        return false;
    }

}

if (!function_exists('cs_wpml_parse_url')) {

    function cs_wpml_parse_url($lang = 'en', $url) {

        $cs_fir_url = home_url('/');
        if (strpos($cs_fir_url, '/' . $lang . '/') !== false) {
            //$cs_fir_url = str_replace('/' . $lang . '/', '/', $cs_fir_url);
        }
        $cs_tail_url = substr($url, strlen($cs_fir_url), strlen($url));

        $cs_trans_url = $cs_fir_url . $lang . '/' . $cs_tail_url;

        return $cs_trans_url;
    }

}

add_filter('icl_ls_languages', 'cs_wpml_ls_filter');
if (!function_exists('cs_wpml_ls_filter')) {

    function cs_wpml_ls_filter($languages) {
        global $sitepress;
        if (strpos(basename($_SERVER['REQUEST_URI']), 'action') !== false) {

            $cs_request_query = str_replace('?', '', basename($_SERVER['REQUEST_URI']));

            $cs_request_query = explode('&', $cs_request_query);

            $cs_request_quer = '';

            $query_count = 1;

            if (is_array($cs_request_query)) {
                foreach ($cs_request_query as $quer) {
                    if (strpos($quer, 'page_id') !== false || strpos($quer, 'lang') !== false) {
                        continue;
                    }
                    if ($query_count == 1) {
                        $cs_request_quer .= $quer;
                    } else {
                        $cs_request_quer .= '&' . $quer;
                    }
                    $query_count++;
                }
            }

            if (is_array($languages) && sizeof($languages) > 0) {
                foreach ($languages as $lang_code => $language) {
                    if (strpos($languages[$lang_code]['url'], '?') !== false) {
                        $languages[$lang_code]['url'] = $languages[$lang_code]['url'] . '&' . $cs_request_quer;
                    } else {
                        $languages[$lang_code]['url'] = $languages[$lang_code]['url'] . '?' . $cs_request_quer;
                    }
                }
            }
        }
        return $languages;
    }

}
