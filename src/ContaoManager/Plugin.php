<?php

/*
 * This file is part of the Craffft Discourse SSO Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Craffft\ContaoDiscourseSSOBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Craffft\ContaoDiscourseSSOBundle\CraffftContaoDiscourseSSOBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CraffftContaoDiscourseSSOBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
                ->setReplace(['discourse-sso']),
        ];
    }
}
