<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NodeResource\Pages;
use App\Models\Node;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('behind_proxy')
                    ->helperText('If you are running the daemon behind a proxy such as Cloudflare, select this to have the daemon skip looking for certificates on boot.')
                    ->required(),
                Forms\Components\TextInput::make('memory')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('memory_overallocate')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('disk')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('disk_overallocate')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('upload_size')
                    ->required()
                    ->integer()
                    ->default(100),
                Forms\Components\TextInput::make('daemonListen')
                    ->required()
                    ->integer()
                    ->label('Daemon Port')
                    ->default(8080),
                Forms\Components\TextInput::make('daemonSFTP')
                    ->required()
                    ->integer()
                    ->label('Daemon SFTP Port')
                    ->default(2022),
                Forms\Components\TextInput::make('daemonBase')
                    ->required()
                    ->maxLength(191)
                    ->default('/home/daemon-files'),

                Forms\Components\ToggleButtons::make('public')
                    ->label('Node Visibility')
                    ->inline()
                    ->default(true)
                    ->helperText('By setting a node to private you will be denying the ability to auto-deploy to this node.')
                    ->options([
                        true => 'Public',
                        false => 'Private',
                    ])
                    ->colors([
                        true => 'warning',
                        false => 'danger',
                    ])
                    ->icons([
                        true => 'heroicon-m-eye',
                        false => 'heroicon-m-lock-closed',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\IconColumn::make('health')
                    ->alignCenter()
                    ->state(fn (Node $node) => $node->systemInformation()['version'] ?? false)
                    ->tooltip(fn (Node $node) => $node->systemInformation()['version'] ?? $node->systemInformation()['exception'] ?? 'Not Connected')
                    ->trueIcon('heroicon-m-heart')
                    ->default(false),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fqdn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('memory')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disk')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daemonBase')
                    ->searchable(),
                Tables\Columns\IconColumn::make('scheme')
                    ->label('SSL')
                    ->trueIcon('heroicon-m-lock-closed')
                    ->falseIcon('heroicon-m-lock-open')
                    ->state(fn (Node $node) => $node->scheme === 'https'),
                Tables\Columns\IconColumn::make('public')
                    ->trueIcon('heroicon-m-eye')
                    ->falseIcon('heroicon-m-eye-slash')
                    ->sortable(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->label('Servers')
                    ->icon('heroicon-m-server-stack'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListNodes::route('/'),
            'create' => Pages\CreateNode::route('/create'),
            'edit' => Pages\EditNode::route('/{record}/edit'),
        ];
    }
}
