<?php

namespace App\Transformers\Api\Application;

use App\Models\ApiKey;
use App\Services\Acl\Api\AdminAcl;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use League\Fractal\TransformerAbstract;
use Webmozart\Assert\Assert;

abstract class BaseTransformer extends TransformerAbstract
{
    public const RESPONSE_TIMEZONE = 'UTC';

    protected Request $request;

    /** @var string[] */
    protected array $availableIncludes = [];

    /** @var string[] */
    protected array $defaultIncludes = [];

    final public function __construct()
    {
        // Transformers allow for dependency injection on the handle method.
        if (method_exists($this, 'handle')) {
            Container::getInstance()->call([$this, 'handle']);
        }
    }

    /**
     * Return the resource name for the JSONAPI output.
     */
    abstract public function getResourceName(): string;

    /**
     * @return array<string, mixed>
     *
     * Transforms a Model into a representation that can be shown to regular users of the API.
     */
    abstract public function transform($model): array; // @phpstan-ignore missingType.parameter

    /**
     * Sets the request on the instance.
     */
    public function setRequest(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Returns a new transformer instance with the request set on the instance.
     */
    public static function fromRequest(Request $request): static
    {
        return (new static())->setRequest($request);
    }

    /**
     * Determine if the API key loaded onto the transformer has permission
     * to access a different resource. This is used when including other
     * models on a transformation request.
     */
    protected function authorize(string $resource): bool
    {
        $allowed = [ApiKey::TYPE_ACCOUNT, ApiKey::TYPE_APPLICATION];

        $token = $this->request->user()->currentAccessToken();
        if (!$token instanceof ApiKey || !in_array($token->key_type, $allowed)) {
            return false;
        }

        // If this is not a deprecated application token type we can only check that
        // the user is a root admin at the moment. In a future release we'll be rolling
        // out more specific permissions for keys.
        if ($token->key_type === ApiKey::TYPE_ACCOUNT) {
            return $this->request->user()->isRootAdmin();
        }

        return AdminAcl::check($token, $resource);
    }

    /**
     * Create a new instance of the transformer and pass along the currently
     * set API key.
     *
     * @template T of \App\Transformers\Api\Application\BaseTransformer
     *
     * @param  class-string<T>  $abstract
     * @return T
     *
     * @noinspection PhpDocSignatureInspection
     */
    protected function makeTransformer(string $abstract)
    {
        Assert::subclassOf($abstract, self::class);

        return $abstract::fromRequest($this->request);
    }

    /**
     * Return an ISO-8601 formatted timestamp to use in the API response.
     */
    protected function formatTimestamp(string $timestamp): string
    {
        return CarbonImmutable::createFromFormat(CarbonInterface::DEFAULT_TO_STRING_FORMAT, $timestamp)
            ->setTimezone(self::RESPONSE_TIMEZONE)
            ->toAtomString();
    }
}
