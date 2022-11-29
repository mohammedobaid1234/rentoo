<?php 

namespace Modules\BriskCore\Traits;

trait ModelActiveTrait {

    public function __construct(){
        parent::__construct();
        
        $this->appends[] = 'active_title';
        $this->appends[] = 'active';
    }

    public function scopeWhereActive($query, $active){
        if((int) trim($active)){
            return $query->active();
        }

        return $query->disable();
    }

    public function scopeDisable($query){
        return $query->whereNotNull('deactivated_by');
    }

    public function scopeActive($query){
        return $query->whereNull('deactivated_by');
    }

    public function getActiveAttribute(){
        if($this->deactivated_by){
            return 0;
        }

        return 1;
    }

    public function getActiveTitleAttribute(){
        if($this->deactivated_by){
            return "مجمد";
        }

        return "فعال";
    }
}