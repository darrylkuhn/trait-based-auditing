<?php namespace App\Traits;

use Log;

trait Auditable
{
    public static function bootAuditable()
    {
        self::created( function ($obj)
        {
            $obj->auditCreate();
            return true;
        } );
    }

    /**
     * Writes to the log whenever this object is saved to the database
     *
     * @return void
     */
    public function auditCreate()
    {
        $className = get_class( $this );
        Log::info( "$className({$this->id}) was created", ['event' => 'create', 'entity' => "$className({$this->id})"] );
    }

}