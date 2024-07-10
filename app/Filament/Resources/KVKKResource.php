<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KVKKResource\Pages;
use App\Filament\Resources\KVKKResource\RelationManagers;
use App\Models\KVKK;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KVKKResource extends Resource
{
    protected static ?string $model = KVKK::class;

    protected static ?string $navigationGroup = "KVKK and Privacy Policy";
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("title"),
                TextInput::make("description")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("title")
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKVKK::route('/'),
            'create' => Pages\CreateKVKK::route('/create'),
            'edit' => Pages\EditKVKK::route('/{record}/edit'),
        ];
    }
}
