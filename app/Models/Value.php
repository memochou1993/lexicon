<?php

namespace App\Models;

use App\Models\Traits\HasForms;
use App\Models\Traits\HasLanguages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Key $key
 */
class Value extends Model
{
    use HasFactory;
    use HasLanguages;
    use HasForms;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'key_id',
        'laravel_through_key',
    ];

    /**
     * Get the key that owns the value.
     *
     * @return BelongsTo
     */
    public function key(): BelongsTo
    {
        return $this->belongsTo(Key::class);
    }

    /**
     * @return Project
     */
    public function getCachedProject(): Project
    {
        $tag = sprintf('%s:%d', $this->getTable(), $this->getKey());

        return Cache::tags($tag)->sear('project', fn() => $this->key->project);
    }

    /**
     * @return Language
     */
    public function getCachedLanguage(): Language
    {
        $tag = sprintf('%s:%d', $this->getTable(), $this->getKey());

        return Cache::tags($tag)->sear('language', fn() => $this->languages->first());
    }

    /**
     * @return Form
     */
    public function getCachedForm(): Form
    {
        $tag = sprintf('%s:%d', $this->getTable(), $this->getKey());

        return Cache::tags($tag)->sear('form', fn() => $this->forms->first());
    }

    /**
     * @return Key
     */
    public function getCachedKey(): Key
    {
        $tag = sprintf('%s:%d', $this->getTable(), $this->getKey());

        return Cache::tags($tag)->sear('key', fn() => $this->key);
    }
}
