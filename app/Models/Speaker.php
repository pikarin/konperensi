<?php

namespace App\Models;

use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
        'business-leader' => 'Business Leader',
        'charisma' => 'Charismatic Speaker',
        'first-time' => 'First Time Speaker',
        'hometown-hero' => 'Hometown Hero',
        'humaitarian' => 'Works in Humanitarian Field',
        'twitter-incluencer' => 'large Twitter Following',
        'youtube-influencer' => 'large YouTube Following',
        'open-source' => 'Open Source Creator / Maintainer',
        'unique-perspective' => 'Unique Perspective',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'qualifications' => 'array',
        ];
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required(),
            Forms\Components\FileUpload::make('avatar')
                ->avatar()
                ->directory('avatars')
                ->imageEditor()
                ->maxSize(1024 * 1024 * 10),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required(),
            Forms\Components\Textarea::make('bio')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('twitter_handle')
                ->required(),
            Forms\Components\CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options(self::QUALIFICATIONS)
                ->columns(3)
                ->required(),
        ];
    }
}
