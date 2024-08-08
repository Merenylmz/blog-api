<?php

namespace App\Filament\Resources;

use App\Actions\Blog\CreateBlogAction;
use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use DateTime;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationGroup = "Operation";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    
    public static function form(Form $form): Form
    {
        $userId = auth()->id();

        return $form
            ->schema([
                TextInput::make("title")->required()->reactive()->live()->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required(),
                Textarea::make("description")->required(),
                Hidden::make("userId")->default($userId),
                Select::make("isitActive")->options([
                    true=>"Aktif",
                    false=>"Pasif"
                ])->label("Durum")->visible(fn()=>auth()->user()->hasRole("super_admin")),
                TagsInput::make("tags"),
                Select::make("categoryId")->options(Category::where("status", true)->pluck("title", "id"))->label("Kategori"),
                FileUpload::make("fileUrl")->disk("public")->directory("blogs")->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    return (string) str($file->getClientOriginalName())->prepend(Carbon::now()->timestamp."_blog_".auth()->id());
                }),
                DatePicker::make("starterDate")->label("Starter Date")->minDate(now()->subDay()),
                DatePicker::make("finishDate")->label("Finish Date")->after("starterDate")->minDate(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id"),
                TextColumn::make("title"),
                IconColumn::make("isitActive")->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function(){
                        Cache::has("allBlog") && Cache::forget("allBlog");
                    }),
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
