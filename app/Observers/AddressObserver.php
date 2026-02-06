<?php

namespace App\Observers;

use App\Models\Address;

class AddressObserver
{
    public function saving(Address $address): void
    {
        if ($address->is_default) {
            Address::where('user_id', $address->user_id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }
    }

    public function creating(Address $address): void
    {
        if (!Address::where('user_id', $address->user_id)->exists()) {
            $address->is_default = true;
        }
    }

    /**
     * Handle the Address "deleted" event.
     */
    public function deleted(Address $address): void
    {
        if ($address->is_default) {
            Address::where('user_id', $address->user_id)
                ->first()?->update(['is_default' => true]);
        }
    }

    /**
     * Handle the Address "restored" event.
     */
    public function restored(Address $address): void
    {
        //
    }

    /**
     * Handle the Address "force deleted" event.
     */
    public function forceDeleted(Address $address): void
    {
        //
    }
}
