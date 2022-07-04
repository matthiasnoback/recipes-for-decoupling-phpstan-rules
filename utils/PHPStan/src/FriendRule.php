<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use ReflectionException;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
final class FriendRule implements Rule
{
    public function __construct(private readonly ReflectionProvider $reflectionProvider)
    {
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
        if (! $node->name instanceof Identifier) {
            // Dynamic method call, we can't analyze it
            return [];
        }

        $objectType = $scope->getType($node->var);
        if (! $objectType instanceof ObjectType) {
            /*
             * We can't find out what type of object this method is
             * called on
             */
            return [];
        }

        try {
            $methodReflection = $this->reflectionProvider
                ->getClass($objectType->getClassName())
                ->getNativeReflection()
                ->getMethod($node->name->toString());
        } catch (ReflectionException) {
            // Could not find the actual method in the code, nothing to analyze
            return [];
        }

        $friendOfAttributes = $methodReflection->getAttributes(FriendOf::class);
        if ($friendOfAttributes === []) {
            /*
             * The method has no `#[FriendOf]` attributes, so it's
             * okay to call this method
             */
            return [];
        }

        $thisClassType = new ObjectType($scope->getClassReflection() ->getName());

        foreach ($friendOfAttributes as $attribute) {
            /** @var FriendOf $instance */
            $instance = $attribute->newInstance();
            $friendClassType = (new ObjectType($instance->friendClass));

            if ($friendClassType->isSuperTypeOf($thisClassType)->yes()) {
                return [];
            }
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method call %s::%s() is only allowed inside friend classes',
                    $objectType->getClassName(),
                    $methodReflection->getName()
                )
            )->build(),
        ];
    }
}
