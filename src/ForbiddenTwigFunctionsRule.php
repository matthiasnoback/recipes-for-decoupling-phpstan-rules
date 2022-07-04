<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\NodeTraverser;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
final class ForbiddenTwigFunctionsRule implements Rule
{
    /**
     * @param list<string> $forbiddenFunctions
     */
    public function __construct(
        private readonly string $templateDir,
        private readonly array $forbiddenFunctions,
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

        if (! isset($node->getArgs()[0])) {
            // The method call has no arguments
            return [];
        }

        $firstArgument = $node->getArgs()[0];
        $firstArgumentType = $scope->getType($firstArgument->value);
        if (! $firstArgumentType instanceof ConstantStringType) {
            // The first argument is not a constant string
            return [];
        }

        $templateName = $firstArgumentType->getValue();

        // Load the template
        $loader = new FilesystemLoader($this->templateDir);

        $source = $loader->getSourceContext($templateName);

        // Parse the template
        $twig = new Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new DebugExtension());

        $nodeTree = $twig->parse($twig->tokenize($source));

        // Traverse the node tree and collect all variable names
        $visitor = new CollectFunctionCalls();
        $nodeTraverser = new NodeTraverser($twig, [$visitor]);
        $nodeTraverser->traverse($nodeTree);

        $errors = [];

        foreach ($visitor->functionCalls() as $functionCall) {
            $functionName = $functionCall->getAttribute('name');

            if (in_array($functionName, $this->forbiddenFunctions, true,)) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'Template uses forbidden function %s',
                    $functionName,
                ))
                    ->file(
                        $functionCall->getSourceContext()
                            ->getPath()
                        ?: $functionCall->getSourceContext()
                            ->getName()
                    )
                    ->line($functionCall->getTemplateLine())
                    ->build();
            }
        }

        return $errors;
    }
}
