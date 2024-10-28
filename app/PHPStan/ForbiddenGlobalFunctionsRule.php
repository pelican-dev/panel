<?php

namespace App\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

class ForbiddenGlobalFunctionsRule implements Rule
{
    private array $forbiddenFunctions;

    public function __construct(array $forbiddenFunctions = ['app', 'resolve'])
    {
        $this->forbiddenFunctions = $forbiddenFunctions;
    }

    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        /** @var FuncCall $node */
        if ($node->name instanceof Node\Name) {
            $functionName = (string) $node->name;
            if (in_array($functionName, $this->forbiddenFunctions, true)) {
                return [
                    sprintf('Usage of global function "%s" is forbidden.', $functionName),
                ];
            }
        }

        return [];
    }
}
