<?php

namespace App\Enums;

enum NodeJwtTokenType: string
{
    case BackupDownload = 'backup_download';
    case FileDownload = 'file_download';
    case FileUpload = 'file_upload';
    case ServerTransfer = 'server_transfer';
    case Websocket = 'websocket';
}
