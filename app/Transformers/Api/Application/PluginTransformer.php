<?php

namespace App\Transformers\Api\Application;

use App\Models\Plugin;

class PluginTransformer extends BaseTransformer
{
    /**
     * Return the resource name for the JSONAPI output.
     */
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
            'category' => $model->category,
            'url' => $model->url,
            'update_url' => $model->update_url,
            'namespace' => $model->namespace,
            'class' => $model->class,
            'panels' => $model->panels ? explode(',', $model->panels) : null,
            'panel_version' => $model->panel_version,
            'composer_packages' => $model->composer_packages ? json_decode($model->composer_packages, true, 512, JSON_THROW_ON_ERROR) : null,
            'meta' => [
                'status' => $model->status,
                'status_message' => $model->status_message,
                'load_order' => $model->load_order,
                'is_compatible' => $model->isCompatible(),
                'update_available' => $model->isUpdateAvailable(),
                'can_enable' => $model->canEnable(),
                'can_disable' => $model->canDisable(),
            ],
        ];
    }
}
