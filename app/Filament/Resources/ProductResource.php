<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Closure;
use Faker\Provider\Text;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('slug', Str::slug($state));
                        })
                        ->required(),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->disabled()
                        ->required(),
                ])->columns(),
                Forms\Components\Card::make([
                    Forms\Components\Select::make('brands')
                        ->label('Brands')
                        ->relationship('brands', 'name')
                        ->options(Brand::all()->pluck('name', 'id'))
                        ->searchable()
                        ->multiple()
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                        ]),
                    Forms\Components\Select::make('categories')
                        ->label('Categories')
                        ->relationship('categories', 'name')
                        ->options(Category::all()->pluck('name', 'id'))
                        ->searchable()
                        ->multiple()
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                        ]),
                ])->columns(),
                Forms\Components\Card::make([
                    Forms\Components\RichEditor::make('content')
                        ->label('Description')
                ]),
                Forms\Components\Card::make([
                    Forms\Components\Repeater::make('variants')
                        ->schema([
                            Forms\Components\TextInput::make('type')
                                ->required(),
                            Forms\Components\TextInput::make('value')
                                ->required()
                        ])
                        ->columns()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TagsColumn::make('brands.name')
                    ->label('Brands'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
