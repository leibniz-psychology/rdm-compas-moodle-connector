<?php
/**
 * Formatting FUnctions.
 *
 * @link       https://edwiser.org
 * @since      1.0.0
 *
 * @package    Edwiser Bridge
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists( 'getCCLicense' ) ) {
    /**
     *
     * get HTML code of given CC license
     *
     * @param string $var CC license as given in Moodle
     *
     * @return string html code
     */
    function getCCLicense( $var ) {
        $licenses = array(
            'CC0 1.0 Universal' => '<i class="fa-brands fa-creative-commons"></i><i class="fa-brands fa-creative-commons-zero"></i> <a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank" rel="noopener">CC0 1.0 Universal</a>',
            'CC BY 4.0' => '<i class="fa-brands fa-creative-commons"></i><i class="fa-brands fa-creative-commons-by"></i> <a href="http://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener">CC BY 4.0</a>',
            'CC BY-SA 4.0' => '<i class="fa-brands fa-creative-commons"></i><i class="fa-brands fa-creative-commons-by"></i><i class="fa-brands fa-creative-commons-sa"></i> <a href="http://creativecommons.org/licenses/by-sa/4.0" target="_blank" rel="noopener">CC BY-SA 4.0</a>',
        );
        return $licenses[$var];
    }
}
