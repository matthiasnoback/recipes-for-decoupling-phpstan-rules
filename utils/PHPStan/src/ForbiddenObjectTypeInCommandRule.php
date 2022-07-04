<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use Symfony\Component\Console\Command\Command;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
final class ForbiddenObjectTypeInCommandRule implements Rule
{
    private readonly ObjectType $forbiddenType;

    public function __construct(string $forbiddenType)
    {
        $this->forbiddenType = new ObjectType($forbiddenType);
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->forbiddenType->isSuperTypeOf($scope->getType($node->var))->yes()) {
            return [];
        }

        if (! $scope->isInClass()) {
            // The call is made outside a class; okay for now
            return [];
        }

        if (in_array(Command::class, $scope->getClassReflection() ->getParentClassesNames(), true)) {
            // One of the parent classes of this class is `Command`
            return [];
        }

        // This call is inside a class that is not a `Command`
        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Object of type %s is used in a class that does not extend %s',
                    $this->forbiddenType->getClassName(),
                    Command::class
                )
            )->build(),
        ];
    }
}
