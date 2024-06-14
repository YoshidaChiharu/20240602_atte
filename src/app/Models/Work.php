<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function rest() {
        return $this->hasMany('App\Models\Rest');
    }

    // 休憩時間算出メソッド
    public function restTime() {
        $rests = $this->rest;

        $rest_time = 0;
        foreach($rests as $rest) {
            if($rest->finished_at) {
                $start = strtotime($rest->began_at);
                $end = strtotime($rest->finished_at);
                $rest_time += $end - $start;
            }
        }

        return gmdate('H:i:s', $rest_time);
    }

    // 勤務時間算出メソッド
    public function workTime() {
        if(empty($this->finished_at)) {
            return null;
        }

        $start = strtotime($this->began_at);
        $end = strtotime($this->finished_at);
        $rest_time = strtotime($this->restTime()) - strtotime('00:00:00');
        $work_time = gmdate('H:i:s', $end - $start - $rest_time);

        return $work_time;
    }

}
