<?php namespace App\Traits;

use Log;

trait Auditable
{
    public static function bootAuditable()
    {
        if ( !self::manuallyAudit('read') )
        {
            self::loading( function ($obj)
            {
                $obj->auditRead();
                return true;
            } );
        }

        if ( !self::manuallyAudit('create') )
        {
            self::created( function ($obj)
            {
                $obj->auditCreate();
                return true;
            } );
        }

        if ( !self::manuallyAudit('update') )
        {
            self::updated( function ($obj)
            {
                $obj->auditUpdate();
                return true;
            } );
        }

        if ( !self::manuallyAudit('delete') )
        {
            self::deleted( function ($obj)
            {
                $obj->auditDelete();
                return true;
            } );
        }
    }

    /**
     * Writes to the log whenever this object is filled from the database
     *
     * @return void
     */
    public function auditRead()
    {
        $className = get_class( $this );
        Log::info( "$className({$this->id}) was read", ['event' => 'read', 'entity' => "$className({$this->id})"] );
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

    /**
     * Writes to the log whenever this object is updated. Include a list of items
     *
     * @return void
     */
    public function auditUpdate()
    {
        $className = get_class( $this );
        Log::info( "$className({$this->id}) was updated", ['event' => 'update', 'entity' => "$className({$this->id})", 'changes' => $this->getModelChanges()] );
    }

    /**
     * Writes to the log whenever this object is deleted (soft delete or hard)
     *
     * @return void
     */
    public function auditDelete()
    {
        $className = get_class( $this );
        Log::info( "$className({$this->id}) was deleted", ['event' => 'delete', 'entity' => "$className({$this->id})"] );
    }

    /**
     * Register a loading model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function loading( $callback )
    {
        static::registerModelEvent( 'loading', $callback );
    }

    public function getObservableEvents()
    {
        return array_merge( parent::getObservableEvents(), ['loading'] );
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function newFromBuilder( $attributes=array(), $connection=null )
    {
        $instance = parent::newFromBuilder( $attributes, $connection );

        // fire a loading event so we can fire actions as appropriate
        $instance->fireModelEvent( 'loading' );

        return $instance;
    }

    /**
     * Helper function to build an array with a list of the things we've
     * changed on this model.
     *
     * @return array
     */
    private function getModelChanges()
    {
        $changes = array();
        $dirty = self::getDirty();

        foreach ( $dirty as $attribute => $newValue )
        {
            if ( $newValue != self::getOriginal($attribute) )
            {
                $change = [ 'attribute' => $attribute ];
                $change['original'] = self::getOriginal( $attribute );
                $change['current'] = $newValue;

                $changes[] = $change;
            }
        }

        return $changes;
    }

    /**
     * In some cases we don't want to automatically audit events
     *
     * @param string $action (e.g. read, create, etc...)
     * @return bool
     */
    public static function manuallyAudit( $action )
    {
        if ( isset(self::$manuallyAudit) &&
             isset(self::$manuallyAudit[$action]) &&
             self::$manuallyAudit[$action] === true )
        {
            return true;
        }

        return false;
    }

}