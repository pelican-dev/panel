<?php

namespace App\Transformers\Api\Application;

use App\Models\Plugin;

class PluginTransformer extends BaseTransformer
{
    public function getResourceName(): string
    {
        return Plugin::RESOURCE_NAME;
    }

    /**
     * @param  Plugin  $model
     */
    public function transform($model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'author' => $model->author,
            'version' => $model->version,
            'description' => $model->description,
            'category' => $model->category->value,
            'url' => $model->url,
            'update_url' => $model->update_url,
            'namespace' => $model->namespace,
            'class' => $model->class,
            'panels' => $model->panels,
            'panel_version' => $model->panel_version,
            'status' => $model->status->value,
            'status_message' => $model->status_message,
            'load_order' => $model->load_order,
            'is_compatible' => $model->isCompatible(),
            'is_update_available' => $model->isUpdateAvailable(),
            'has_settings' => $model->hasSettings(),
            'can_enable' => $model->canEnable(),
            'can_disable' => $model->canDisable(),
        ];
    }
}
