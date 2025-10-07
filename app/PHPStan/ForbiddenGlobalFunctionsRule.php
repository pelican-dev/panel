<?php

namespace App\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class ForbiddenGlobalFunctionsRule implements Rule
{
    /** @var string[] */
    public const FORBIDDEN_FUNCTIONS = ['app', 'resolve'];

    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        /** @var FuncCall $node */
        if ($node->name instanceof Name) {
            $functionName = (string) $node->name;
            if (in_array($functionName, self::FORBIDDEN_FUNCTIONS, true)) {
                return [
                    RuleErrorBuilder::message(sprintf(
                        'Usage of global function "%s" is forbidden.',
                        $functionName,
                    ))->identifier('myCustomRules.forbiddenGlobalFunctions')->build(),
                ];
            }
        }

        return [];
    }
}
