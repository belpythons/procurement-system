<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Register broadcast channel authorization callbacks.
| The import channel is scoped per import job — any authenticated user
| who initiated the import may listen on it.
|
*/

Broadcast::channel('import.{importLogId}', function ($user, $importLogId) {
    // Allow any authenticated user to listen on import channels.
    // For stricter control, verify $user->id matches the ImportLog's user_id.
    $importLog = \App\Models\ImportLog::find($importLogId);
    return $importLog && $importLog->user_id === $user->id;
});
