<?php

class LHLEnvironmentDetectorService {
    public function __construct() {
    }

    /**
     * Detect the environment ID based on the current environment.
     */
    public function detect_environment_id() {
        $environment_guess = false;

        if (defined('WP_ENV')) {
            $environment_guess = WP_ENV;
        } elseif (defined('WP_ENVIRONMENT_TYPE')) {
            $environment_guess = WP_ENVIRONMENT_TYPE;
        } elseif (defined('WP_ENVIRONMENT')) {
            $environment_guess = WP_ENVIRONMENT;
        } elseif (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
            $environment_guess = $_ENV['PANTHEON_ENVIRONMENT'];
        }

        /**
         * If we have a guess, we'll try to match it to one of the known environments.
         */
        if (!empty($environment_guess)) {
            if (strpos($environment_guess, 'loc') !== false) {
                $autodetected_id = 'loc';
            } elseif (
                (strpos($environment_guess, 'dev') !== false)
            ) {
                $autodetected_id = 'dev';
            } elseif (
                (strpos($environment_guess, 'stag') !== false) ||
                (strpos($environment_guess, 'stg') !== false) ||
                (strpos($environment_guess, 'test') !== false)
            ) {
                $autodetected_id = 'stg';
            } elseif (
                (strpos($environment_guess, 'prod') !== false) ||
                (strpos($environment_guess, 'live') !== false)
            ) {
                $autodetected_id = 'prd';
            }
        }

        if (!empty($autodetected_id)) {
            return $autodetected_id;
        }

        /**
         * If we still haven't detected an environment, we'll try to guess based on the URL.
         */
        $environment_patterns = array(
            'loc' => array(
                '/lndo.site/i',
            ),
            'dev' => array(
                '/dev-/i',
            ),
            'stg' => array(
                '/stage-/i',
                '/staging/i',
                '/test-/i',
            ),
            'prd' => array(
                '/production/i',
                '/live-/i',
            ),
        );

        $url = get_site_url();
        foreach ($environment_patterns as $environment_id => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $url)) {
                    return $environment_id;
                }
            }
        }

        return $environment_id;
    }
}
