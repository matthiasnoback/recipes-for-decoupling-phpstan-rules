<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertiesNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Type;

final class AutoIncrementingModelIdRule implements Rule
{
    public function getNodeType(): string
    {
        return ClassPropertiesNode::class;
    }

    /**
     * @param ClassPropertiesNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $scope->isInClass()) {
            // Should not happen
            return [];
        }

        if (! in_array(Model::class, $scope->getClassReflection() ->getParentClassesNames(), true)) {
            // This class does not extend `Model`
            return [];
        }

        $type = $this->defaultValueTypeOfIncrementingProperty($node, $scope);

        if (! $type instanceof ConstantBooleanType) {
            /*
             * The default value of `$incrementing` is not
             * recognized as a boolean, we can't analyze it
             */
            return [];
        }

        if ($type->getValue() === false) {
            /*
             * Indeed, we want the default value of `$incrementing`
             * to be `false`
             */
            return [];
        }

        return [RuleErrorBuilder::message('This model has an auto-incrementing ID')->build()];
    }

    private function defaultValueTypeOfIncrementingProperty(ClassPropertiesNode $node, Scope $scope): Type
    {
        foreach ($node->getProperties() as $property) {
            if ($property->getName() !== 'incrementing') {
                continue;
            }

            return $scope->getType($property->getDefault());
        }

        /*
         * The default value inherited from the `Model` parent
         * class is supposed to be true
         */
        return new ConstantBooleanType(true);
    }
}
