<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('type')->options([
                    'question' => 'Question',
                    'solution' => 'Solution',
                    'hand_note' => 'Hand Note',
                    'slide' => 'Slide',
                    'library' => 'e-Library',
                ])->required(),
                Forms\Components\FileUpload::make('file')->required(),
                Forms\Components\Select::make('semester_id')->relationship('semester','name')->required(),
                Forms\Components\Select::make('department_id')->relationship('department','name')->required(),
                Forms\Components\Select::make('year_id')->relationship('year','name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('type')->sortable()->searchable(),
                TextColumn::make('semester.name')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('year.name')->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options([
                    'question' => 'Question',
                    'solution' => 'Solution',
                    'hand_note' => 'Hand Note',
                    'slide' => 'Slide',
                    'library' => 'e-Library',
                ]),
                Tables\Filters\SelectFilter::make('semester')->relationship('semester','name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
