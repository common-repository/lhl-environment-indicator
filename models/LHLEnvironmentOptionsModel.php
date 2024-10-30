<?php

class LHLEnvironmentOptionsModel {

    private $options = false;
    private $options_name = 'lhlnvnd_environment_options';

    private $defaults = array(
        'environment_id'               =>    'autodetect',
        'autodetected_id'              =>    '',
        'disable_fallback_autodetect'  =>    0,
    );

    private $available_environment_ids = [
        'def' => [
            'label' =>  '- Hide Indicator -',
            'value' => "def",
        ],
        'autodetect' => [
            'label' => "Autodetect",
            'value' => "auto",
        ],
        "loc" => [
            'label' => 'Local',
            'value' => "loc",
        ],
        "dev" => [
            'label' => 'Development',
            'value' => "dev",
        ],
        "stg" => [
            'label' => 'Stage',
            'value' => "stg",
        ],
        "prd" => [
            'label' => 'Production',
            'value' => "prd",
        ],
        "cus" => [
            'label' => 'Custom',
            'value' => "cus",
        ]
    ];

    public function get_options_name() {
        return $this->options_name;
    }

    public function __construct() {
        $this->options = get_option($this->options_name);
    }

    public function default_options() {
        return $this->defaults;
    }

    public function available_environment_ids() {
        return $this->available_environment_ids;
    }

    public function get_options() {
        if (false ==  $this->options) {
            return $this->default_options();
        }
        return $this->options;
    }

    public function get_option($key) {
        $options = $this->get_options();
        if (isset($options[$key])) {
            return $options[$key];
        }
        return false;
    }

    public function update_option($key, $value) {
        $options = $this->get_options();
        $options[$key] = $value;
        update_option($this->options_name, $options);
    }
}
