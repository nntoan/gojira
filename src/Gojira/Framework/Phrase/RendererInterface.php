<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gojira\Framework\Phrase;

/**
 * GoJira phrase renderer
 *
 * @api
 * @package Gojira\Framework\Phrase\Renderer
 * @author  Toan Nguyen <me@nntoan.com>
 */
interface RendererInterface
{
    /**
     * Render source text
     *
     * @param [] $source
     * @param [] $arguments
     * @return string
     */
    public function render(array $source, array $arguments);
}
