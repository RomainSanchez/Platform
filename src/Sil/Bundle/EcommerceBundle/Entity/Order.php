<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements OrderInterface
{
    /**
     * @var Collection
     */
    protected $invoices;

    public function __construct()
    {
        parent::__construct();
        $this->invoices = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode(?string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * Add invoice.
     *
     * @param Invoice $invoice
     *
     * @return Order
     */
    public function addInvoice(Invoice $invoice)
    {
        $this->invoices[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice.
     *
     * @param Invoice $invoice
     */
    public function removeInvoice(Invoice $invoice)
    {
        $this->invoices->removeElement($invoice);
    }

    /**
     * Get invoices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * @return Invoice
     */
    public function getLastDebitInvoice()
    {
        return $this->invoices->filter(function ($item) {
            return $item->getType() === Invoice::TYPE_DEBIT;
        })->first();
    }

    public function __toString()
    {
        if ($this->number !== null) {
            return (string) $this->number;
        }

        return (string) $this->id;
    }
}
