<?php

/**
 * Logman configuration file.
 */

return [

    /*
     * Default events to record if $recordEvents property is not defined on the model.
     * Accepts an array of event names: 'created', 'updated', 'deleted'.
     */
    'default_record_events' => ['created', 'updated', 'deleted'],

    /*
     * User attribute lookup keys for generating user_name.
     * The trait will look through these keys in order until it finds a non-null value.
     * Keys are resolved from the authenticated user model (Auth::user()).
     */
    'user_attributes' => ['name', 'email'],

    /*
     * IP address detection headers list, in order of preference.
     * The trait will check these headers to determine the client's IP address.
     * If none are found, it falls back to REMOTE_ADDR.
     */
    'ip_headers' => ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'],

    /*
     * Whether to automatically log the model's primary key in the description.
     * Defaults to true for backward compatibility.
     */
    'include_rowid_in_description' => true,

];