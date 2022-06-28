<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenParentClassRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Utils\PHPStan\ForbiddenParentClassRule;

final class ForbiddenParentClassRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/extends-abstract-controller.php'],
            [['Parent class Symfony\Bundle\FrameworkBundle\Controller\AbstractController is forbidden', 7]]
        );
    }

    public function testExtendsNothing(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-class-extends-nothing.php'], []);
    }

    public function testExtendsSomethingElse(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-class-extends-something-else.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ForbiddenParentClassRule(AbstractController::class,);
    }
}
