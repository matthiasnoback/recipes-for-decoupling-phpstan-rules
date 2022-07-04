<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\ObjectType;
use Twig\Environment;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
final class NoEntityInTemplateRule implements Rule
{
    public function __construct(
        private readonly EntityDetermination $determine,
    ) {
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
        $twigEnvironment = new ObjectType(Environment::class);
        if (! $twigEnvironment
            ->isSuperTypeOf($scope->getType($node->var))
            ->yes()) {
            // The object is not a Twig `Environment` instance
            return [];
        }

        if (! $node->name instanceof Identifier
            || $node->name->toString() !== 'render') {
            // The method is called dynamically, or is not `render()`
            return [];
        }

        if (! isset($node->getArgs()[1])) {
            // The method call has no second argument
            return [];
        }

        $templateVars = $node->getArgs()[1]
            ->value;

        $arrayType = $scope->getType($templateVars);
        if (! $arrayType instanceof ConstantArrayType) {
            return [];
        }

        $valueTypes = $arrayType->getValueTypes();

        $errors = [];

        foreach ($valueTypes as $valueType) {
            if (! $valueType instanceof ObjectType) {
                continue;
            }

            if (
                $this->determine->isEntity($valueType->getClassReflection())
            ) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Entity of type %s should not ' .
                        'be passed to a template',
                        $valueType->getClassName(),
                    ),
                )->build();
            }
        }

        return $errors;
    }
}
