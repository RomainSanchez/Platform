<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\CoreAdmin;

class EmailLink extends CoreAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.email.email_link';
}
