<?php 
namespace VanguardLTE
{
    class GameSummary extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'game_summary';
        protected $fillable = [
            'user_id',
            'category_id', 
            'shop_id',
            'name',
            'date',
            'totalbet',
            'totalwin',
            'total_deal',
            'total_mileage',
            'type',
            'updated_at'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }

}
