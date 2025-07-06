<?php

namespace App\Models;

use Filament\Forms;
use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Venue extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'region' => Region::class,
        ];
    }

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required(),
            Forms\Components\TextInput::make('city')
                ->required(),
            Forms\Components\TextInput::make('country')
                ->required(),
            Forms\Components\TextInput::make('postal_code')
                ->required(),
            Forms\Components\Select::make('region')
                ->enum(Region::class)
                ->options(Region::class),
            Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                ->collection('venue-images')
                ->multiple()
                ->image(),
        ];
    }
}
