<?php namespace Agency\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface MediaInterface {

    /**
     * Returns the type of a media object
     *
     * @return string
     */
    public function type();

     /**
     * Returns the url of a media object
     *
     * @return string
     */
    public function url();
}
