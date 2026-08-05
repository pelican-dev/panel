<?php

namespace App\Extensions\Dedoc\Scramble;

use Dedoc\Scramble\Extensions\OperationExtension;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types\ArrayType;
use Dedoc\Scramble\Support\Generator\Types\ObjectType;
use Dedoc\Scramble\Support\Generator\Types\Type;
use Dedoc\Scramble\Support\RouteInfo;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionNamedType;
use ReflectionParameter;
use Throwable;

/**
 * Fills in operation and request body descriptions by looking at the route's FormRequest rules.
 * Without this most endpoints just show up with a bare field list and no explanation of what
 * anything actually does.
 */
class ApiRequestDocumentationExtension extends OperationExtension
{
    public function handle(Operation $operation, RouteInfo $routeInfo): void
    {
        $this->describeOperation($operation, $routeInfo);

        $fields = $this->resolveFields($routeInfo);

        $this->describeParameters($operation, $fields);

        if ($fields === []) {
            return;
        }

        $fieldSummary = implode("\n", array_map(
            fn (string $name, string $description): string => '- `'.$name.'`: '.$description,
            array_keys($fields),
            $fields,
        ));

        $this->describeRequestBody($operation, $fieldSummary, $fields);
    }

    /**
     * @return array<string, string>
     */
    private function resolveFields(RouteInfo $routeInfo): array
    {
        $requestClass = $this->requestClass($routeInfo);

        if ($requestClass === null) {
            return [];
        }

        try {
            $request = new $requestClass();
            $rules = is_callable([$request, 'rules'])
                ? call_user_func([$request, 'rules'])
                : [];
        } catch (Throwable) {
            return [];
        }

        if (!is_array($rules) || $rules === []) {
            return [];
        }

        $fields = [];

        foreach ($rules as $name => $rule) {
            if (!is_string($name)) {
                continue;
            }

            $fields[$name] = $this->fieldDescription($name);
        }

        return $fields;
    }

    private function describeOperation(Operation $operation, RouteInfo $routeInfo): void
    {
        if (strlen(trim($operation->description)) >= 220) {
            return;
        }

        $resource = $this->resourceName($routeInfo);
        $action = strtolower($routeInfo->methodName() ?? $operation->method);
        $details = match ($action) {
            'index', 'list' => "Returns the collection of {$resource} resources available to the authenticated caller. Results are represented using the API transformer and may include pagination, filtering, sorting, or related resources where supported by this endpoint.",
            'view', 'show', 'find' => "Returns the requested {$resource} with the attributes and related data exposed by the API transformer. The resource must be accessible to the authenticated caller and is identified by the route parameter.",
            'store', 'create' => "Creates a new {$resource} in the Panel. The submitted values are validated before the resource is persisted and any required related records or runtime configuration are initialized. A successful response contains the newly created {$resource}.",
            'update', 'edit' => "Updates the selected {$resource} using the supplied values. Only fields accepted by this endpoint are applied, and the request is validated before changes are persisted. The response contains the updated resource.",
            'delete', 'destroy', 'remove' => "Removes the selected {$resource} from the Panel. This operation can permanently delete the resource and may also trigger cleanup of related records or external runtime state. The route parameter identifies the resource to remove.",
            default => "Performs the {$action} operation for the selected {$resource}. The request is validated and authorization is checked before the operation is executed. The response describes the resulting state or operation status.",
        };

        $existing = trim($operation->description);
        $operation->description($existing === '' ? $details : $existing."\n\n".$details);
    }

    private function resourceName(RouteInfo $routeInfo): string
    {
        $controller = $routeInfo->className();

        if (is_string($controller)) {
            $name = class_basename($controller);
            $name = preg_replace('/Controller$/', '', $name) ?: $name;
            $name = preg_replace('/(?<!^)([A-Z])/', ' $1', $name) ?: $name;

            return strtolower($name);
        }

        $segments = array_values(array_filter(explode('/', trim($routeInfo->route->uri(), '/')), fn (string $segment): bool => !str_contains($segment, '{')));
        $resource = end($segments) ?: 'resource';

        return str_ends_with($resource, 's') ? substr($resource, 0, -1) : $resource;
    }

    /**
     * @return class-string<FormRequest>|null
     */
    private function requestClass(RouteInfo $routeInfo): ?string
    {
        $action = $routeInfo->reflectionAction();

        if (!$action && is_string($routeInfo->route->getAction('uses')) && str_contains($routeInfo->route->getAction('uses'), '@')) {
            [$controller, $method] = explode('@', $routeInfo->route->getAction('uses'), 2);

            try {
                $action = new \ReflectionMethod($controller, $method);
            } catch (Throwable) {
                return null;
            }
        }

        if (!$action) {
            return null;
        }

        foreach ($action->getParameters() as $parameter) {
            $class = $this->parameterClass($parameter);

            if ($class !== null && is_a($class, FormRequest::class, true)) {
                return $class;
            }
        }

        return null;
    }

    /**
     * @return class-string|null
     */
    private function parameterClass(ReflectionParameter $parameter): ?string
    {
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return null;
        }

        return $type->getName();
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function describeParameters(Operation $operation, array $fields): void
    {
        foreach ($operation->parameters as $parameter) {
            $name = $parameter->name;
            $field = $fields[$name] ?? $fields[$name.'.*'] ?? null;

            if ($field !== null && $parameter->description === '') {
                $parameter->description($field);
            }

            if ($parameter->description === '') {
                $parameter->description($this->parameterDescription($name, $parameter->in));
            }
        }
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function describeRequestBody(Operation $operation, string $fieldSummary, array $fields): void
    {
        if (!$operation->requestBodyObject) {
            return;
        }

        $bodyDescription = "Request body fields are validated according to the rules below:\n".$fieldSummary;

        if ($operation->requestBodyObject->description === '') {
            $operation->requestBodyObject->description($bodyDescription);
        }

        foreach ($operation->requestBodyObject->content as $schema) {
            if (!$schema instanceof Schema || !$schema->type instanceof ObjectType) {
                continue;
            }

            foreach ($fields as $name => $description) {
                $this->resolveProperty($schema->type, $name)?->setDescription($description);
            }
        }
    }

    /**
     * Resolves a dotted validation field path (e.g. `limits.memory` or `allocation.additional.*`)
     * to the corresponding nested schema property, traversing into objects and array items as
     * needed so descriptions land on the actual nested property instead of only root-level ones.
     */
    private function resolveProperty(ObjectType $root, string $name): ?Type
    {
        $current = $root;

        foreach (explode('.', $name) as $segment) {
            if ($segment === '*') {
                if (!$current instanceof ArrayType) {
                    return null;
                }

                $current = $current->items;

                continue;
            }

            if (!$current instanceof ObjectType || !$current->hasProperty($segment)) {
                return null;
            }

            $property = $current->getProperty($segment);

            if (!$property instanceof Type) {
                return null;
            }

            $current = $property;
        }

        return $current;
    }

    private function parameterDescription(string $name, string $location): string
    {
        $descriptions = [
            'server' => 'Server identifier used to select the server this operation acts on.',
            'node' => 'Node identifier used to select the node this operation acts on.',
            'user' => 'User identifier used to select the account or subuser.',
            'database' => 'Database identifier belonging to the selected server.',
            'database_host' => 'Database host identifier used for the connection target.',
            'backup' => 'Backup UUID belonging to the selected server.',
            'schedule' => 'Schedule identifier belonging to the selected server.',
            'task' => 'Task identifier belonging to the selected schedule.',
            'allocation' => 'Allocation identifier belonging to the selected server or node.',
            'egg' => 'Egg identifier used to select the egg configuration.',
            'mount' => 'Mount identifier used to select the mount configuration.',
            'role' => 'Role identifier used to select the role.',
            'plugin' => 'Plugin identifier used to select the plugin.',
            'identifier' => 'The API key identifier to remove.',
            'fingerprint' => 'The SSH key fingerprint to remove.',
            'external_id' => 'External identifier assigned by an integrating system.',
        ];

        if (isset($descriptions[$name])) {
            return $descriptions[$name];
        }

        $label = str_replace(['_', '-'], ' ', $name);
        $label = ucfirst($label);

        return $label.' parameter used by this '.$location.' operation.';
    }

    private function fieldDescription(string $name): string
    {
        $baseName = str_ends_with($name, '.*') ? substr($name, 0, -2) : $name;
        $specificDescriptions = [
            'external_id' => 'Identifier supplied by an external system to correlate this resource with another application.',
            'user' => 'ID of the user who owns the server and is allowed to access it.',
            'egg' => 'ID of the egg that defines the server software, startup command, and environment variables.',
            'docker_image' => 'Docker image used as the server container image when it starts.',
            'startup' => 'Command executed inside the container when the server starts.',
            'environment' => 'Environment variables passed to the selected egg and made available to the server process.',
            'skip_scripts' => 'Whether the egg installation and update scripts should be skipped during deployment.',
            'oom_killer' => 'Whether the operating system may terminate the server when it exceeds its memory limit.',
            'limits' => 'Resource limits that control how much CPU, memory, disk, and I/O capacity the server may use.',
            'limits.memory' => 'Maximum memory available to the server, in megabytes.',
            'limits.swap' => 'Swap memory available to the server, in megabytes; `-1` commonly means unlimited.',
            'limits.disk' => 'Maximum disk space available to the server, in megabytes.',
            'limits.io' => 'Relative block I/O weight used to prioritize this server against other workloads.',
            'limits.threads' => 'CPU thread or core affinity assigned to the server, when supported by the node.',
            'limits.cpu' => 'Maximum CPU capacity available to the server, expressed as a percentage of a CPU core.',
            'feature_limits' => 'Limits for how many databases, allocations, and backups can be created for the server.',
            'feature_limits.databases' => 'Maximum number of databases that may be assigned to the server.',
            'feature_limits.allocations' => 'Maximum number of network allocations that may be assigned to the server.',
            'feature_limits.backups' => 'Maximum number of backups that may be stored for the server.',
            'allocation.default' => 'ID of the primary network allocation assigned to the server.',
            'allocation.additional.*' => 'ID of an additional network allocation assigned to the server.',
            'deploy' => 'Automatic deployment settings used to choose a suitable node and network allocation.',
            'deploy.locations' => 'Node location IDs where the server may be deployed. This option is deprecated in favor of tags.',
            'deploy.locations.*' => 'ID of a node location allowed for automatic server deployment.',
            'deploy.tags' => 'Node tags used to restrict automatic deployment to matching nodes.',
            'deploy.tags.*' => 'A node tag that must match for automatic deployment to use that node.',
            'deploy.dedicated_ip' => 'Whether automatic deployment should assign a dedicated IP address to the server.',
            'deploy.port_range' => 'Port ranges that automatic deployment may use when selecting allocations.',
            'deploy.port_range.*' => 'A port or port range available for automatic allocation, such as `25565` or `25565-25575`.',
            'start_on_completion' => 'Whether the server should be started automatically after creation and deployment finish.',
        ];

        if (isset($specificDescriptions[$name])) {
            return $specificDescriptions[$name];
        }

        $semanticDescriptions = [
            'root' => 'Root directory relative to the server container filesystem.',
            'directory' => 'Directory path relative to the server container filesystem.',
            'file' => 'File path relative to the server container filesystem.',
            'files' => 'List of file or directory paths relative to the selected root directory.',
            'name' => 'Display name for the resource or operation result.',
            'description' => 'Human-readable description shown in the Panel.',
            'key' => 'Egg environment variable name to update.',
            'value' => 'New value for the selected field or environment variable.',
            'url' => 'Remote URL from which the requested resource is downloaded.',
            'format' => 'Requested serialization or export format.',
            'extension' => 'Archive format used for the generated file.',
            'per_page' => 'Maximum number of resources returned per page.',
            'page' => 'Page number to return for a paginated collection.',
            'sort' => 'Sort field; prefix the field with `-` for descending order.',
            'include' => 'Comma-separated related resources to include when authorized.',
            'filter' => 'Filter values applied to the collection query.',
            'node_id' => 'Destination node identifier for the operation.',
            'allocation_id' => 'Primary allocation identifier for the operation.',
            'allocation_additional' => 'Additional allocation identifiers to assign.',
            'password' => 'Password value used for authentication or credential rotation.',
            'email' => 'Email address for the account.',
            'username' => 'Username displayed for the account.',
            'command' => 'Command sent to the server console.',
            'external_id' => 'Identifier supplied by an external system for correlating this resource.',
            'user_id' => 'ID of the user associated with this resource.',
            'egg_id' => 'ID of the egg configuration associated with this server.',
            'image' => 'Container image used to run the server process.',
            'environment' => 'Environment variables made available to the server process.',
            'limits' => 'Resource limits applied to the server.',
            'feature_limits' => 'Maximum numbers of related resources allowed for the server.',
            'allocation' => 'Network allocation settings for the server.',
            'deploy' => 'Automatic deployment settings for selecting a suitable node and allocation.',
        ];
        $label = ucfirst(str_replace(['_', '-'], ' ', $baseName));

        $description = $semanticDescriptions[$baseName] ?? $label.' field';

        if (str_ends_with($name, '.*') && !str_ends_with($description, ' item')) {
            $description .= ' item';
        }

        return rtrim($description, '.').'.';
    }
}
