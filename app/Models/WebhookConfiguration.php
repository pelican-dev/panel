<?php

namespace App\Models;

use App\Events\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\Filesystem;

class WebhookConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'description',
        'events',
    ];

    protected function casts(): array
    {
        return [
            'events' => 'json',
        ];
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function scopeForEvent(Builder $builder, Event $event): Builder
    {
        return $builder->whereJsonContains('events', $event::class);
    }

    public static function allPossibleEvents() {
        return static::allModelEvents();
    }

    public static function allModelEvents()
    {
        $eventTypes = ['created', 'updated', 'deleted'];
        $models = static::discoverModels();

        $events = [];
        foreach ($models as $model) {
            foreach ($eventTypes as $eventType) {
                $events[] = "eloquent.$eventType: $model";
            }
        }

        return $events;
    }

    public static function discoverModels(): array
    {
        $namespace = "App\\Models\\";
        $directory = app_path('Models');
        $filesystem = app(Filesystem::class);

        $models = [];
        foreach ($filesystem->allFiles($directory) as $file) {
            $models[] = $namespace . str($file->getFilename())
                ->replace([DIRECTORY_SEPARATOR, '.php'], ['\\', '']);
        }

        return $models;
    }
}
