<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KvkkResource\Pages;
use App\Filament\Resources\KvkkResource\RelationManagers;
use App\Models\Kvkk;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use League\CommonMark\Input\MarkdownInput;

class KvkkResource extends Resource
{
    protected static ?string $model = Kvkk::class;

    protected static ?string $navigationGroup = "KVKK and Privacy Policy";
    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("title"),
                MarkdownEditor::make("description")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id"),
                TextColumn::make("title"),
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
            'index' => Pages\ListKvkks::route('/'),
            'create' => Pages\CreateKvkk::route('/create'),
            'edit' => Pages\EditKvkk::route('/{record}/edit'),
        ];
    }
}
