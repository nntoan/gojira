<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gojira\Tests;

use Gojira\Application;
use Gojira\Command\GreetCommand;
use Gojira\Command\VersionCommand;

/**
 * Application test cases.
 *
 * @author Toan Nguyen <me@nntoan.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    const NAME    = 'Test';
    const VERSION = '1.0.1';

    private $app;

    /**
     * Sets up the test app.
     */
    public function setUp()
    {
        $this->app = new Application(self::NAME, self::VERSION);
    }

    /**
     * Tests whether the constructor instantiates the correct dependencies and
     * correctly sets the name on the Console's Application.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('Symfony\Component\Console\Application', $this->app['console']);

        $this->assertEquals(self::NAME, $this->app['console']->getName());
        $this->assertEquals(self::VERSION, $this->app['console']->getVersion());
    }

    public function testCustomInputOutput()
    {
        $input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->app['console'] = $this->getMock('Symfony\Component\Console\Application');
        $this->app['console']->expects($this->once())->method('run')->with($input, $output);


        $this->app->run($input, $output);
    }

    public function testClosureCommand()
    {
        $invoked = false;
        $command = $this->app->command('closure-command', function () use (&$invoked) {
            $invoked = true;
        });

        $this->assertInstanceOf('Symfony\Component\Console\Command\Command', $command);
        $this->assertTrue($this->app['console']->has('closure-command'));

        $command->run(
            $this->getMock('Symfony\Component\Console\Input\InputInterface'),
            $this->getMock('Symfony\Component\Console\Output\OutputInterface')
        );

        $this->assertTrue($invoked);
    }

    /**
     * Tests the command method to see if the command is properly set and the
     * Gojira application is added as container.
     */
    public function testCommand()
    {
        $this->assertFalse($this->app['console']->has('version'));

        $this->app->command(new VersionCommand());

        $this->assertTrue($this->app['console']->has('version'));

        $this->assertSame($this->app, $this->app['console']->get('version')->getContainer());
    }

}
