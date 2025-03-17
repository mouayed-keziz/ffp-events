<?php

namespace App\Traits\Livewire;

use App\Forms\ExhibitorFormActions;

trait HasPrices
{
    /**
     * Calculate the total price for all selected items
     */
    public function calculateTotalPrice()
    {
        $actions = new ExhibitorFormActions();
        $this->totalPrice = $actions->calculateTotalPrice($this->formData, $this->preferred_currency);
    }

    /**
     * Change the preferred currency and recalculate prices
     */
    public function changeCurrency($currency)
    {
        if (in_array($currency, ['DZD', 'EUR', 'USD'])) {
            $this->preferred_currency = $currency;
            $this->calculateTotalPrice();
        }
    }
}
