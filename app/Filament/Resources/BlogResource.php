<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use DateTime;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    
    public static function form(Form $form): Form
    {
        $userId = auth()->user()->id;

        // // Filament formunda checkbox alanını ekleyin:
        // $form->field('categories', 'Kategoriler')
        // ->checkboxList(Category::all());

        return $form
            ->schema([
                TextInput::make("title")->required(),
                Textarea::make("description")->required(),
                TextInput::make("userId")->default($userId)->hidden(),
                Select::make("isitActive")->options([
                    true=>"Aktif",
                    false=>"Pasif"
                ])->label("Durum")->required(),
                TagsInput::make("tags"),
                CheckboxList::make("categories")->options(Category::where("status", true)->pluck("title", "id")),
                FileUpload::make("fileUrl")->disk("public")->directory("blogs"),
                DatePicker::make("starterDate")->label("Starter Date")->minDate(now()),
                DatePicker::make("finishDate")->label("Finish Date")->after("starterDate"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
