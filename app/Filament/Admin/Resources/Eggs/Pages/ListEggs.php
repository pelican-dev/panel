<?php

namespace App\Filament\Admin\Resources\Eggs\Pages;

use App\Filament\Admin\Resources\Eggs\EggResource;
use App\Filament\Components\Actions\ExportEggAction;
use App\Filament\Components\Actions\ImportEggAction;
use App\Filament\Components\Actions\UpdateEggAction;
use App\Filament\Components\Actions\UpdateEggBulkAction;
use App\Filament\Components\Tables\Filters\TagsFilter;
use App\Models\Egg;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ListEggs extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = EggResource::class;

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('id')
                    ->label('Id')
                    ->hidden(),
                ImageColumn::make('image')
                    ->label('')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->image
                        ? $record->image
                        : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OTYuNjgiIGhlaWdodD0iNTAxLjY0Ij48ZGVmcz48bGluZWFyR3JhZGllbnQgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjAiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiBncmFkaWVudFRyYW5zZm9ybT0ibWF0cml4KDE4MC40MDQ0OCwtMTI4LjYyOTIxLC0xMjguNjI5MjEsLTE4MC40MDQ0OCw5My40MTE4ODgsMzkzLjMzNTYzKSIgc3ByZWFkTWV0aG9kPSJwYWQiIGlkPSJBIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiMyYWE0ZGQiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM1YmNhZWUiLz48L2xpbmVhckdyYWRpZW50PjxjbGlwUGF0aCBpZD0iQiI+PHBhdGggZD0iTTAgMzc2LjIzaDM3Mi41MVYwSDB6IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMzE5LjggLTI0OC41MSkiLz48L2NsaXBQYXRoPjxjbGlwUGF0aCBpZD0iQyI+PHBhdGggZD0iTTAgMzc2LjIzaDM3Mi41MVYwSDB6IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjEwLjMgLTMzNC41NykiLz48L2NsaXBQYXRoPjxjbGlwUGF0aCBpZD0iRCI+PHBhdGggZD0iTTAgMzc2LjIzaDM3Mi41MVYwSDB6IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTk4Ljg5IC0yODUuNzgpIi8+PC9jbGlwUGF0aD48bGluZWFyR3JhZGllbnQgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjAiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiBncmFkaWVudFRyYW5zZm9ybT0ibWF0cml4KDE5MS43MzAzMywxMTAuNDI2OTYsMTEwLjQyNjk2LC0xOTEuNzMwMzMsMjIuNjY4MjIsMTExLjk2ODQ5KSIgc3ByZWFkTWV0aG9kPSJwYWQiIGlkPSJFIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNhMGRiZTYiLz48c3RvcCBvZmZzZXQ9Ii4yMTkiIHN0b3AtY29sb3I9IiNhMGRiZTYiLz48c3RvcCBvZmZzZXQ9Ii42IiBzdG9wLWNvbG9yPSIjZjZmOWZiIi8+PC9saW5lYXJHcmFkaWVudD48bGluZWFyR3JhZGllbnQgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjAiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiBncmFkaWVudFRyYW5zZm9ybT0ibWF0cml4KDE3OS41OTU1LDEwNi43ODY1MSwxMDYuNzg2NTEsLTE3OS41OTU1LDMxLjI5NDk4OSw5Mi41NDMyOTcpIiBzcHJlYWRNZXRob2Q9InBhZCIgaWQ9IkYiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iIzJhYTRkZCIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzViY2FlZSIvPjwvbGluZWFyR3JhZGllbnQ+PGNsaXBQYXRoIGlkPSJHIj48cGF0aCBkPSJNMCAzNzYuMjNoMzcyLjUxVjBIMHoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yMTMuMzcgLTE4MikiLz48L2NsaXBQYXRoPjwvZGVmcz48cGF0aCBkPSJNMjA3LjggMjk1LjM1bDIxLjI2LTE1LjJoMGwyMi42OC0xNi42MmgwYzguMDItNS43NSAxNi4xOS0xMS41MSAyNS41OS0xNC44MWgwYzkuNDQtMy4zMiAxOS42NC00LjUgMjkuNTgtMy4yNmgwYTYwLjE2IDYwLjE2IDAgMCAxIDEyLjg5IDMuMDVoMGMuMS4wNC4yMS4wNy4zMS4xMWgwYy03LjM0IDMuNDMtMTUuMzcgNi42OS0yMi43MSAxMC4xMmgwbC01OS42MyAyOC4yNGgwbC0yMC40MiAxMC4xMWgwYy0xLjE2LjUyLTIuNjYgMS4zOS0zLjk3IDEuMzloMGMtMi4zNiAwLTUuNTgtMy4xMy01LjU4LTMuMTMiIHRyYW5zZm9ybT0ibWF0cml4KDEuMzMzMzMzIDAgMCAtMS4zMzMzMzMgMCA1MDEuNjQpIiBmaWxsPSJ1cmwoI0EpIi8+PGcgZmlsbD0iIzBlMjEzMiI+PHBhdGggZD0iTTAgMGwuMzEuMTFDLTcuMDMgMy41NC0xNS4wNiA2LjgtMjIuNCAxMC4yM2wtNTkuNjMgMjguMjQtMjAuNDIgMTAuMTFjLTEuMTYuNTItMi42NiAxLjM5LTMuOTcgMS4zOS0yLjM2IDAtNS41OC0zLjEzLTUuNTgtMy4xM2wyMS4yNi0xNS4yIDIyLjY4LTE2LjYyQy02MC4wNCA5LjI3LTUxLjg3IDMuNTEtNDIuNDcuMjFjOS40NC0zLjMyIDE5LjY0LTQuNSAyOS41OC0zLjI2QTYwLjE2IDYwLjE2IDAgMCAxIDAgMG0tMTEyLjExIDYzLjE2YzUuNTggMS4xOCAxMS4xLS4xNiAxNi4yNS0yLjM1IDcuNzktMy4zMSAxNC45OC03Ljg3IDIyLjc0LTExLjI2bDU0LjI1LTI1LjYxIDIwLjQ4LTkuNzcgMTcuNzItOC4xNWM0LjU4LTEuOTkgOS42NS0zLjYgMTQuNzctMy4yOSAyLjQ3LjE0IDQuOTIuNzYgNy4yOSAxLjYgNy4zMi0yMC4xOCAxMS4zMi00MS45OCAxMS4zMi02NC43MyAwLTEwMy44OS04My4zOS0xODguMTEtMTg2LjI2LTE4OC4xMS0zOS4yNSAwLTc1LjY2IDEyLjI2LTEwNS42OCAzMy4yMSA2LjE0IDEyLjEyIDE0Ljg0IDIzLjIxIDI0LjYzIDMyLjA3IDUuOTUgNS4zNyAxMi41MyAxMC4wMyAxOS42MiAxMy43OCAxNy40OSA5LjIyIDM2LjU0IDE0Ljk2IDU1LjY5IDE5LjU2IDIxLjg4IDUuMjYgNDQuMDggMTMuODMgNjEuMDYgMjkuMzcgMTguNTYgMTYuOTkgMzAuNiA0Mi4yMyAyOS45NiA2Ny41OS0uMzIgMTIuNzItMy43MiAyNS4zMS0xMC4xNSAzNi4zMS02LjM3IDEwLjkxLTE2LjAyIDE5LjQ1LTI1Ljc5IDI3LjI2LTUuOTQgNC43NC0xMS45OCA5LjMzLTE4LjE3IDEzLjczLTYuMSA0LjMzLTEyLjg5IDcuOTktMTguNTMgMTIuOS0zLjYzIDMuMTUtNi41OSA3LjA0LTUuNjggMTIuMTEgMS4yNyA3LjAxIDcuNzYgMTIuMzYgMTQuNDggMTMuNzgiIHRyYW5zZm9ybT0ibWF0cml4KDEuMzMzMzMzIDAgMCAtMS4zMzMzMzMgNDI2LjQgMTcwLjI5MzMzKSIgY2xpcC1wYXRoPSJ1cmwoI0IpIi8+PHBhdGggZD0iTTAgMGM0LjI3IDAgNy43My0zLjUyIDcuNzMtNy44NlM0LjI3LTE1LjcyIDAtMTUuNzItNy43NC0xMi4yLTcuNzQtNy44Ni00LjI3IDAgMCAwIiB0cmFuc2Zvcm09Im1hdHJpeCgxLjMzMzMzMyAwIDAgLTEuMzMzMzMzIDI4MC40IDU1LjU0NjY2NykiIGNsaXAtcGF0aD0idXJsKCNDKSIvPjwvZz48cGF0aCBkPSJNMCAwYzUuNjQtNC45MSAxMi40My04LjU3IDE4LjUzLTEyLjkgNi4xOS00LjQgMTIuMjMtOC45OSAxOC4xNy0xMy43MyA5Ljc3LTcuODEgMTkuNDItMTYuMzUgMjUuNzktMjcuMjYgNi40My0xMSA5LjgzLTIzLjU5IDEwLjE1LTM2LjMxLjY0LTI1LjM2LTExLjQtNTAuNi0yOS45Ni02Ny41OUMyNS43LTE3My4zMyAzLjUtMTgxLjktMTguMzgtMTg3LjE2Yy0xOS4xNS00LjYtMzguMi0xMC4zNC01NS42OS0xOS41Ni03LjA5LTMuNzUtMTMuNjctOC40MS0xOS42Mi0xMy43OC05Ljc5LTguODYtMTguNDktMTkuOTUtMjQuNjMtMzIuMDctMi44NC01LjU5LTUuMTMtMTEuMzktNi43My0xNy4zLTIuNTYgMTEuODEtMS40OCAyNC4wMSAzLjE4IDM1LjE2IDUuNDcgMTMuMDggMTUuMDcgMjQuMDUgMjUuNjYgMzMuMjcgMi43NCAyLjI3IDUuNjYgNC4zMyA4LjY3IDYuMjUgOC42NCA1LjUxIDE3Ljc3IDEwLjI2IDI3LjExIDE0LjQ3IDQuODQgMi4xOCA5Ljc0IDQuMjMgMTQuNjUgNi4yNiA2LjE1IDIuNTQgMTIuMzIgNC45MyAxOC4xNiA4LjE0bDMuMzIgMS45YzE2LjMzIDkuNzEgMzEuMDcgMjIuMzcgNDIuMyAzNy43NCAyLjM5IDMuMjcgNC42IDYuNjUgNi40OSAxMC4yM2wuNjYgMS4yOWMuNTIgMS4wMiAxIDIuMDQgMS40NyAzLjA4IDMuOTIgOC43NSA2LjIgMTguMzQgNi4yNCAyNy45NS4wOSAxOC45Ny0xMC4yMiAzMy43Mi0yNC4zOSA0NS4zMy01LjggNC43NS0xMi4wMSA4Ljk4LTE3Ljk4IDEzLjUxQy0xOS45Ni0xNy4zNi0zMS4wNC03Ljc0LTM0LjUgNS40NmMtMi4xMSA4LjAzLTEuMTYgMTYuODUgMy41OCAyMy43OSAxLjA1IDEuNTUgMi4yNSAyLjk5IDMuNTYgNC4zMy45Ny45OSAyLjE2IDIuMTQgNC4zMyAzLjgtMTkuMS00LjIxLTI3LjY5LTE2LjQ5LTI5LjA3LTM0LjMxLTExLjMyIDE5LjEtNi4zNCAzMi41MyA5LjYzIDQ1LjcyLTEuOTItLjU5LTMuODgtMS4wNy01Ljg2LTEuMzhhMzAuNSAzMC41IDAgMCAwLTEuODktLjI0Yy03LjM4LS43NC0xNC40OCAxLjQ4LTE5LjY1IDYuODYtMi4wMiAyLjExLTQuMTYgNC43Ny01LjI5IDcuNDcuODktLjU1IDMuMDItMS4yMyAzLjI5LTEuMyA0LjY0LTEuMjQgNi45MS0xLjI3IDExLjczLTEuMTUgMTQuNjMuMzggMjguNTkgNi40NyA0My4wNyA4LjI1IDE2LjA4IDEuOTkgMzUuMTggMS4yOSA0Ny4wOC0xMS4zMSA0Ljk3LTUuMjcgNy43Ni0xMi41NSA4LjQ5LTE5LjY5bC4zMy0zLjE1IDQ1LjQ2LTIxLjggNzQuNTgtMzUuNTQgMTEuMTEtNS40NWMtMi40NS0xLjE2LTUuMDMtMi4zNy03LjY4LTMuMy0yLjM3LS44NC00LjgyLTEuNDYtNy4yOS0xLjYtNS4xMi0uMzEtMTAuMTkgMS4zLTE0Ljc3IDMuMjktNS45NyAyLjU4LTExLjg1IDUuMzYtMTcuNzIgOC4xNWwtMjAuNDggOS43Ny01NC4yNSAyNS42MWMtNy43NiAzLjM5LTE0Ljk1IDcuOTUtMjIuNzQgMTEuMjYtNS4xNSAyLjE5LTEwLjY3IDMuNTMtMTYuMjUgMi4zNS02LjcyLTEuNDItMTMuMjEtNi43Ny0xNC40OC0xMy43OEMtNi41OSA3LjA0LTMuNjMgMy4xNSAwIDBtMy42NyA0MC45M2MwLTQuMzQgMy40Ny03Ljg2IDcuNzQtNy44NnM3LjczIDMuNTIgNy43MyA3Ljg2LTMuNDYgNy44Ni03LjczIDcuODYtNy43NC0zLjUyLTcuNzQtNy44NiIgdHJhbnNmb3JtPSJtYXRyaXgoMS4zMzMzMzMgMCAwIC0xLjMzMzMzMyAyNjUuMTg2NjcgMTIwLjYpIiBjbGlwLXBhdGg9InVybCgjRCkiIGZpbGw9IiNmNWY3ZmEiLz48ZyB0cmFuc2Zvcm09Im1hdHJpeCgxLjMzMzMzMyAwIDAgLTEuMzMzMzMzIDAgNTAxLjY0KSI+PHBhdGggZD0iTTE2OC4yMSAyMjkuOTFjLTEzLjM5LTMuMTctMjYuMDMtOS41NS0zNy45OS0xNi4yMmgwYy0xMy4xNy03LjM1LTI1LjY2LTE1LjktMzcuMDYtMjUuNzhoMGMtMTMuODQtMTItMjYuMDUtMjYuMDYtMzQuNTYtNDIuMzZoMGMtMy44Ni03LjQxLTYuOTEtMTUuMjQtOC45MS0yMy4zNWgwYzQuNjUgOC44NiA5LjY3IDE3LjU0IDE1LjIzIDI1Ljg2aDBjNi4wOSA5LjA5IDEzLjA4IDE3LjU3IDIwLjc4IDI1LjM1aDBjMjAuNTggMjAuNzkgNDUuODkgMzcuMTggNzMuMjMgNDcuNTVoMGM0LjE1IDEuNTggOC4zOSAyLjkzIDEyLjcyIDMuODloMGMxMi4zIDIuNzMgMjYuMjMgMi4wNyAzNC43LTguNDhoMGM3LjY0LTkuNTMgOC4wOS0yMi43NSA3LjAyLTM0LjM3aDBjNS4wNCAxMS43NiA2LjcgMjUuMjYtMS42MSAzNS44M2gwYy0xLjc5IDIuMjctMy44MiA0LjM1LTYuMDkgNi4xNGgwYy02Ljk1OSA1LjQ5NC0xNS4yNDcgNy42MzgtMjMuNzE2IDcuNjM4aDBjLTQuNTg5IDAtOS4yMzEtLjYyOS0xMy43NDQtMS42OTgiIGZpbGw9InVybCgjRSkiLz48cGF0aCBkPSJNMTcxLjY1IDIyNC44NWMtNC4zMy0uOTYtOC41Ny0yLjMxLTEyLjcyLTMuODloMGMtMjcuMzQtMTAuMzctNTIuNjUtMjYuNzYtNzMuMjMtNDcuNTVoMGMtNy43LTcuNzgtMTQuNjktMTYuMjYtMjAuNzgtMjUuMzVoMGMtNS41Ni04LjMyLTEwLjU4LTE3LTE1LjIzLTI1Ljg2aDBhNzYuNDMgNzYuNDMgMCAwIDEtLjk2LTQuMjRoMGE5NS42NiA5NS42NiAwIDAgMS0xLjc2LTE0LjhoMGMtLjA5LTIuNTUtLjA5LTUuMS4wMi03LjY0aDBhOTEuMjUgOTEuMjUgMCAwIDEgLjI2LTQuMDRoMGMuMDctLjg3LjYyLTIuNjMuMzUtMy40NGgwbDIuNDIgNy4zMWgwYzIuNjcgNy4zIDYuMSAxNC41IDEwLjE2IDIxLjA4aDBjNy40MyAxMi4wMiAxNy4yMyAyMi44MyAyOS4wNyAzMC41M2gwYzEyLjY0IDguMjMgMjUuOTYgMTQuMTMgNDAuMjQgMTguOTdoMGMxMS40OSAzLjkgMjMuMjggNy4wNSAzNC42OCAxMS4yMWgwYzEwLjM1IDMuNzkgMjAuNDkgOSAyNy44NCAxNy4yMWgwYy0zLjQtMTAuNTUtMTEuMTgtMTkuMjgtMjAuMjYtMjUuNjNoMGMtOS4wOS02LjM1LTE5LjQ4LTEwLjU1LTI5LjktMTQuMzRoMGMtMTguNzYtNi44NC0zNy4wNS0xMy42MS01Mi4yMy0yNy4wOGgwYy0yMC45MS0xOC41Ny0zMS4wNi00NC4xNS0yNi40OS03MS45NWgwYy0uMzIgMS45MiAyLjYgNi4zMSAzLjQ2IDguMDJoMGE4NS44MSA4NS44MSAwIDAgMCA0LjYyIDguMDdoMGMzLjE5IDQuOTQgNi44NCA5LjU4IDEwLjgzIDEzLjg5aDBjOC4yIDguODUgMTcuNzIgMTYuNDkgMjguMTUgMjIuNTdoMGMxMS4wMiA2LjQzIDIzLjI3IDEwLjU1IDM0Ljk1IDE1LjU5aDBjMTEuOTMgNS4xNSAyMy43MyAxMC44NSAzNC4zMSAxOC40NmgwYzEwLjk3IDcuODggMjAuNiAxNy41OSAyNy45NyAyOC45NGgwYzIuMiAzLjM5IDQuMjcgNy4xNSA1Ljk1IDExLjExaDBjMS4wNyAxMS42Mi42MiAyNC44NC03LjAyIDM0LjM3aDBjLTUuODQxIDcuMjc2LTE0LjI3OSA5Ljg0OC0yMi45OSA5Ljg0OGgwYy0zLjkxOCAwLTcuODkzLS41MjEtMTEuNzEtMS4zNjgiIGZpbGw9InVybCgjRikiLz48L2c+PHBhdGggZD0iTTAgMGM1LjA0IDExLjc2IDYuNyAyNS4yNi0xLjYxIDM1LjgzLTEuNzkgMi4yNy0zLjgyIDQuMzUtNi4wOSA2LjE0LTEwLjczIDguNDctMjQuNjIgOC45OC0zNy40NiA1Ljk0LTEzLjM5LTMuMTctMjYuMDMtOS41NS0zNy45OS0xNi4yMi0xMy4xNy03LjM1LTI1LjY2LTE1LjktMzcuMDYtMjUuNzgtMTMuODQtMTItMjYuMDUtMjYuMDYtMzQuNTYtNDIuMzYtMy44Ni03LjQxLTYuOTEtMTUuMjQtOC45MS0yMy4zNWE3Ni40MyA3Ni40MyAwIDAgMS0uOTYtNC4yNCA5NS42NiA5NS42NiAwIDAgMS0xLjc2LTE0LjhjLS4wOS0yLjU1LS4wOS01LjEuMDItNy42NGE5MS4yNSA5MS4yNSAwIDAgMSAuMjYtNC4wNGMuMDctLjg3LjYyLTIuNjMuMzUtMy40NGwyLjQyIDcuMzFjMi42NyA3LjMgNi4xIDE0LjUgMTAuMTYgMjEuMDggNy40MyAxMi4wMiAxNy4yMyAyMi44MyAyOS4wNyAzMC41MyAxMi42NCA4LjIzIDI1Ljk2IDE0LjEzIDQwLjI0IDE4Ljk3IDExLjQ5IDMuOSAyMy4yOCA3LjA1IDM0LjY4IDExLjIxIDEwLjM1IDMuNzkgMjAuNDkgOSAyNy44NCAxNy4yMS0zLjQtMTAuNTUtMTEuMTgtMTkuMjgtMjAuMjYtMjUuNjMtOS4wOS02LjM1LTE5LjQ4LTEwLjU1LTI5LjktMTQuMzQtMTguNzYtNi44NC0zNy4wNS0xMy42MS01Mi4yMy0yNy4wOC0yMC45MS0xOC41Ny0zMS4wNi00NC4xNS0yNi40OS03MS45NS0uMzIgMS45MiAyLjYgNi4zMSAzLjQ2IDguMDJhODUuODEgODUuODEgMCAwIDAgNC42MiA4LjA3YzMuMTkgNC45NCA2Ljg0IDkuNTggMTAuODMgMTMuODkgOC4yIDguODUgMTcuNzIgMTYuNDkgMjguMTUgMjIuNTcgMTEuMDIgNi40MyAyMy4yNyAxMC41NSAzNC45NSAxNS41OSAxMS45MyA1LjE1IDIzLjczIDEwLjg1IDM0LjMxIDE4LjQ2IDEwLjk3IDcuODggMjAuNiAxNy41OSAyNy45NyAyOC45NEMtMy43NS03LjcyLTEuNjgtMy45NiAwIDBtLTU2Ljk1IDE1Mi41N2MtMTUuOTctMTMuMTktMjAuOTUtMjYuNjItOS42My00NS43MiAxLjM4IDE3LjgyIDkuOTcgMzAuMSAyOS4wNyAzNC4zMS0yLjE3LTEuNjYtMy4zNi0yLjgxLTQuMzMtMy44LTEuMzEtMS4zNC0yLjUxLTIuNzgtMy41Ni00LjMzLTQuNzQtNi45NC01LjY5LTE1Ljc2LTMuNTgtMjMuNzkgMy40Ni0xMy4yIDE0LjU0LTIyLjgyIDI0Ljk5LTMwLjc1IDUuOTctNC41MyAxMi4xOC04Ljc2IDE3Ljk4LTEzLjUxQzguMTYgNTMuMzcgMTguNDcgMzguNjIgMTguMzggMTkuNjVjLS4wNC05LjYxLTIuMzItMTkuMi02LjI0LTI3Ljk1LS40Ny0xLjA0LS45NS0yLjA2LTEuNDctMy4wOC0uMjEtLjQzLS40NC0uODYtLjY2LTEuMjktMS44OS0zLjU4LTQuMS02Ljk2LTYuNDktMTAuMjMtMTEuMjMtMTUuMzctMjUuOTctMjguMDMtNDIuMy0zNy43NC0xLjA5LS42NS0yLjItMS4yOC0zLjMyLTEuOS01Ljg0LTMuMjEtMTIuMDEtNS42LTE4LjE2LTguMTRsLTE0LjY1LTYuMjZjLTkuMzQtNC4yMS0xOC40Ny04Ljk2LTI3LjExLTE0LjQ3LTMuMDEtMS45Mi01LjkzLTMuOTgtOC42Ny02LjI1LTEwLjU5LTkuMjItMjAuMTktMjAuMTktMjUuNjYtMzMuMjctNC42Ni0xMS4xNS01Ljc0LTIzLjM1LTMuMTgtMzUuMTYtMTcuNzQgMTMuMTgtMjcuMiAyOS43My0yOC43NyA0OS41LTI4LjA4IDMyLjk0LTQ1LjA3IDc1LjgyLTQ1LjA3IDEyMi43IDAgMTAzLjkgODMuMzkgMTg4LjEyIDE4Ni4yNSAxODguMTIgNzcuMDUgMCAxNDMuMTgtNDcuMjUgMTcxLjUxLTExNC42NGwtNzQuNTggMzUuNTQtNDUuNDYgMjEuOC0uMzMgMy4xNWMtLjczIDcuMTQtMy41MiAxNC40Mi04LjQ5IDE5LjY5LTExLjkgMTIuNi0zMSAxMy4zLTQ3LjA4IDExLjMxLTE0LjQ4LTEuNzgtMjguNDQtNy44Ny00My4wNy04LjI1LTQuODItLjEyLTcuMDktLjA5LTExLjczIDEuMTUtLjI3LjA3LTIuNC43NS0zLjI5IDEuMyAxLjEzLTIuNyAzLjI3LTUuMzYgNS4yOS03LjQ3IDUuMTctNS4zOCAxMi4yNy03LjYgMTkuNjUtNi44NmEzMC41IDMwLjUgMCAwIDEgMS44OS4yNGMxLjk4LjMxIDMuOTQuNzkgNS44NiAxLjM4IiB0cmFuc2Zvcm09Im1hdHJpeCgxLjMzMzMzMyAwIDAgLTEuMzMzMzMzIDI4NC40OTMzMyAyNTguOTczMzMpIiBjbGlwLXBhdGg9InVybCgjRykiIGZpbGw9IiMwZTIxMzIiLz48L3N2Zz4NCg=='
                    )
                    ->circular()
                    ->imageSize(50),
                TextColumn::make('name')
                    ->label(trans('admin/egg.name'))
                    ->description(fn ($record): ?string => (strlen($record->description) > 120) ? substr($record->description, 0, 120).'...' : $record->description)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->label(trans('admin/egg.servers')),
            ])
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::edit.single.label')),
                ExportEggAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::export.modal.actions.export.label')),
                UpdateEggAction::make()
                    ->iconButton()
                    ->tooltip(trans_choice('admin/egg.update', 1)),
                ReplicateAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::replicate.single.label'))
                    ->modal(false)
                    ->excludeAttributes(['author', 'uuid', 'update_url', 'servers_count', 'created_at', 'updated_at'])
                    ->beforeReplicaSaved(function (Egg $replica) {
                        $replica->author = user()?->email;
                        $replica->name .= ' Copy';
                        $replica->uuid = Str::uuid()->toString();
                    })
                    ->after(fn (Egg $record, Egg $replica) => $record->variables->each(fn ($variable) => $variable->replicate()->fill(['egg_id' => $replica->id])->save()))
                    ->successRedirectUrl(fn (Egg $replica) => EditEgg::getUrl(['record' => $replica])),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->before(fn (&$records) => $records = $records->filter(function ($egg) {
                        /** @var Egg $egg */
                        return $egg->servers_count <= 0;
                    })),
                UpdateEggBulkAction::make()
                    ->before(fn (&$records) => $records = $records->filter(function ($egg) {
                        /** @var Egg $egg */
                        return cache()->get("eggs.$egg->uuid.update", false);
                    })),
            ])
            ->emptyStateIcon('tabler-eggs')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/egg.no_eggs'))
            ->emptyStateActions([
                CreateAction::make(),
                ImportEggAction::make()
                    ->multiple(),
            ])
            ->filters([
                TagsFilter::make()
                    ->model(Egg::class),
            ]);
    }

    /** @return array<Action|ActionGroup>
     * @throws Exception
     */
    protected function getDefaultHeaderActions(): array
    {
        return [
            ImportEggAction::make()
                ->multiple(),
            CreateAction::make(),
        ];
    }
}
