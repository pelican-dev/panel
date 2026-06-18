<?php

namespace App\Enums;

enum NodeJwtScope: string
{
    case BackupDownload = 'backup-download';
    case FileDownload = 'file-download';
    case FileUpload = 'file-upload';
    case ServerTransfer = 'transfer';
    case Websocket = 'websocket';
}
