<?php

namespace App;

trait RecordsActivity{

    // boot and the name of the trait to trigger the boot method on the model class
    protected static function bootRecordsActivity(){
        foreach(static::getActivitiesToRecord() as $event)
        static::$event(function($model) use ($event){
           $model->recordActivity($event);
        });
    }

    protected static function getActivitiesToRecord(){
        return ['created'];
    }

    protected function recordActivity($event){

        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => $this->user_id,
            // below two columns will be added automatically by the polymorphic relationship, refer to activity method here.
            //     'subject_id' => $this->id,
            //     'subject_type' => get_class($this)
        ]);
    }

    protected function getActivityType($event){
        return $event.'_'. strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
}