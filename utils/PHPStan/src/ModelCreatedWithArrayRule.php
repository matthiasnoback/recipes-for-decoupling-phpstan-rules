<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

final class ModelCreatedWithArrayRule implements Rule
{
    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    /**
     * @param StaticCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->isCallToModelCreate($node, $scope)) {
            return [];
        }

        if (count($node->getArgs()) > 1) {
            // Multiple arguments were provided: great!
            return [];
        }

        if (count($node->getArgs()) === 0) {
            return [
                RuleErrorBuilder::message(
                    'Model is created with no arguments, ' .
                    'use explicit arguments instead'
                )->build(),
            ];
        }

        $firstArgument = $node->getArgs()[0];
        if (! $scope->getType($firstArgument->value)->isArray()
            ->yes()) {
            // The only argument is not an array
            return [];
        }

        return [
            RuleErrorBuilder::message(
                'Model is created with an array argument, ' .
                'use explicit arguments instead'
            )->build(),
        ];
    }

    private function isCallToModelCreate(StaticCall $node, Scope $scope): bool
    {
        if (! $node->class instanceof Name) {
            // The class part is dynamic, we can't do anything with it
            return false;
        }

        $type = $scope->resolveTypeByName($node->class);
        if (! (new ObjectType(Model::class))
            ->isSuperTypeOf($type)
            ->yes()) {
            // The class is definitely not a model
            return false;
        }

        if (! $node->name instanceof Identifier) {
            // It's a dynamic method call
            return false;
        }

        return $node->name->toString() === 'create';
    }
}
