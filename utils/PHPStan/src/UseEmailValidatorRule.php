<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use Illuminate\Http\Request;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;

final class UseEmailValidatorRule implements Rule
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->isCallToRequestValidate($node, $scope)) {
            return [];
        }

        if (count($node->getArgs()) === 0) {
            // No arguments provided, nothing to analyze
            return [];
        }

        $firstArgumentNode = $node->getArgs()[0];
        $firstArgumentType = $scope->getType($firstArgumentNode->value);

        if (! $firstArgumentType instanceof ConstantArrayType) {
            // No constant array provided, we can't analyze this
            return [];
        }

        foreach ($firstArgumentType->getValueTypes() as $valueType) {
            if (! $valueType instanceof ConstantStringType) {
                // The value is not a plain string
                continue;
            }

            $parts = explode('|', $valueType->getValue());
            if (! in_array('email', $parts, true)) {
                // The string doesn't contain 'email'
                continue;
            }

            // Return an error on the first use of "email":

            return [RuleErrorBuilder::message('Use App\Models\Email::validator() instead of "email"')->build()];
        }

        return [];
    }

    private function isCallToRequestValidate(MethodCall $node, Scope $scope): bool
    {
        if (! (new ObjectType(Request::class))
            ->isSuperTypeOf($scope->getType($node->var))
            ->yes()) {
            // The object is not an instance of `Request`
            return false;
        }

        if (! $node->name instanceof Identifier) {
            // It's a dynamic method call, we can't analyze it
            return false;
        }

        return $node->name->toString() === 'validate';
    }
}
