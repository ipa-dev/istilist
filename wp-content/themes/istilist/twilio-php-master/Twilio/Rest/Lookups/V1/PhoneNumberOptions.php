<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Lookups\V1;

use Twilio\Options;
use Twilio\Values;

abstract class PhoneNumberOptions
{
    /**
     * @param string $countryCode The country_code
     * @param string $type The type
     * @param string $addOns The add_ons
     * @param string $addOnsData The add_ons_data
     * @return FetchPhoneNumberOptions Options builder
     */
    public static function fetch($countryCode = Values::NONE, $type = Values::NONE, $addOns = Values::NONE, $addOnsData = Values::NONE)
    {
        return new FetchPhoneNumberOptions($countryCode, $type, $addOns, $addOnsData);
    }
}

class FetchPhoneNumberOptions extends Options
{
    /**
     * @param string $countryCode The country_code
     * @param string $type The type
     * @param string $addOns The add_ons
     * @param string $addOnsData The add_ons_data
     */
    public function __construct($countryCode = Values::NONE, $type = Values::NONE, $addOns = Values::NONE, $addOnsData = Values::NONE)
    {
        $this->options['countryCode'] = $countryCode;
        $this->options['type'] = $type;
        $this->options['addOns'] = $addOns;
        $this->options['addOnsData'] = $addOnsData;
    }

    /**
     * The country_code
     *
     * @param string $countryCode The country_code
     * @return $this Fluent Builder
     */
    public function setCountryCode($countryCode)
    {
        $this->options['countryCode'] = $countryCode;
        return $this;
    }

    /**
     * The type
     *
     * @param string $type The type
     * @return $this Fluent Builder
     */
    public function setType($type)
    {
        $this->options['type'] = $type;
        return $this;
    }

    /**
     * The add_ons
     *
     * @param string $addOns The add_ons
     * @return $this Fluent Builder
     */
    public function setAddOns($addOns)
    {
        $this->options['addOns'] = $addOns;
        return $this;
    }

    /**
     * The add_ons_data
     *
     * @param string $addOnsData The add_ons_data
     * @return $this Fluent Builder
     */
    public function setAddOnsData($addOnsData)
    {
        $this->options['addOnsData'] = $addOnsData;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Lookups.V1.FetchPhoneNumberOptions ' . implode(' ', $options) . ']';
    }
}
