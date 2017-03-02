<?php

namespace Dizzy\Cashier;

use Carbon\Carbon;
use LogicException;
use Illuminate\Database\Eloquent\Model;
use Dizzy\Cashier\Mollie\Subscription as MollieSubscription;
use Mollie_API_Object_Customer_Subscription;

/**
 * @property mixed ends_at
 * @property mixed trial_ends_at
 */
class Subscription extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at', 'ends_at',
        'created_at', 'updated_at',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->owner();
    }

    /**
     * Get the model related to the subscription.
     */
    public function owner()
    {
        $model = getenv('MOLLIE_MODEL') ?: config('services.mollie.model', 'App\\User');
        $model = new $model;
        return $this->belongsTo(get_class($model), $model->getForeignKey());
    }

    /**
     * Determine if the subscription is active, on trial, or within its grace period.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->active() || $this->onTrial() || $this->onGracePeriod();
    }

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function active()
    {
        return is_null($this->ends_at) || $this->onGracePeriod();
    }

    /**
     * Determine if the subscription is no longer active.
     *
     * @return bool
     */
    public function cancelled()
    {
        return ! is_null($this->ends_at);
    }

    /**
     * Determine if the subscription is within its trial period.
     *
     * @return bool
     */
    public function onTrial()
    {
        if (! is_null($this->trial_ends_at)) {
            return Carbon::today()->lt($this->trial_ends_at);
        } else {
            return false;
        }
    }

    /**
     * Determine if the subscription is within its grace period after cancellation.
     *
     * @return bool
     */
    public function onGracePeriod()
    {
        if (! is_null($endsAt = $this->ends_at)) {
            return Carbon::now()->lt(Carbon::instance($endsAt));
        } else {
            return false;
        }
    }

    /**
     * Swap the subscription to a new plan.
     *
     * @param  string  $plan
     * @return $this
     */
    public function swap($plan)
    {
        // TODO: Mollie doesn't support plans. Find another way.

        return $this;
    }

    /**
     * Cancel the subscription.
     *
     * @return $this
     */
    public function cancel()
    {
        $subscription = $this->asMollieSubscription();

        MollieSubscription::cancel($subscription->id);

        if ($this->onTrial()) {
            $this->ends_at = $this->trial_ends_at;
        } else {
            // TODO: Immediately cancels the subscription, we will have to calculate this ourselves.
            $this->ends_at = Carbon::createFromTimestamp(
                $subscription->cancelledDatetime
            );
        }

        $this->save();

        return $this;
    }

    /**
     * Cancel the subscription immediately.
     *
     * @return $this
     */
    public function cancelNow()
    {
        $subscription = $this->asMollieSubscription();

        MollieSubscription::cancel($subscription->id);

        $this->markAsCancelled();

        return $this;
    }

    /**
     * Mark the subscription as cancelled.
     *
     * @return void
     */
    public function markAsCancelled()
    {
        $this->fill(['ends_at' => Carbon::now()])->save();
    }

    /**
     * Resume the cancelled subscription.
     *
     * @return $this
     *
     * @throws \LogicException
     */
    public function resume()
    {
        if (! $this->onGracePeriod()) {
            throw new LogicException('Unable to resume subscription that is not within grace period.');
        }

        //TODO: With Mollie there doens't seem to be a way to update subscriptions. Gotta find another way.

        return $this;
    }

    /**
     * Get the subscription as a Mollie subscription object.
     *
     * @return Mollie_API_Object_Customer_Subscription
     */
    public function asMollieSubscription()
    {
        return MollieSubscription::get($this->mollie_id);
    }
}