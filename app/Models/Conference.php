<?php

namespace App\Models;

use Filament\Forms;
use App\Enums\Region;
use App\Enums\Status;
use Livewire\Component as Livewire;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'status' => Status::class,
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'region' => Region::class,
            'venue_id' => 'integer',
        ];
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            Forms\Components\Section::make('Conference Details')
                ->description('Provide some basic information about the conference.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Conference')
                        ->columnSpanFull()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->required(),
                    Forms\Components\DateTimePicker::make('start_date')
                        ->native(false)
                        ->required(),
                    Forms\Components\DateTimePicker::make('end_date')
                        ->native(false)
                        ->required(),

                    Forms\Components\Fieldset::make('status')
                        ->columns(1)
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->enum(Status::class)
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'archived' => 'Archived',
                                ])
                                ->required(),
                            Forms\Components\Toggle::make('is_published')
                                ->label('Published')
                                ->default(false),
                        ]),
                ]),

            Forms\Components\Section::make('Location')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('region')
                        ->live()
                        ->enum(Region::class)
                        ->options(Region::class)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('venue_id', null)),
                    Forms\Components\Select::make('venue_id')
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Venue::getForm())
                        ->editOptionForm(fn ($record) => $record ? Venue::getForm() : [])
                        ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                            return $query->where('region', $get('region'));
                        }),
                ]),

            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('star')
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-m-star')
                    ->visible(function (string $operation) {
                        if ($operation !== 'create') {
                            return false;
                        }

                        if (app()->environment('local')) {
                            return true;
                        }

                        return false;
                    })
                    ->action(function (Livewire $livewire) {
                        $data = Conference::factory()->make()->toArray();

                        $livewire->form->fill($data);
                    }),
            ]),

            // Forms\Components\CheckboxList::make('speakers')
            //     ->relationship('speakers', 'name')
            //     ->options(Speaker::all()->pluck('name', 'id'))
            //     ->required(),
        ];
    }
}
