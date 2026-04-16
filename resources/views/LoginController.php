<?php

namespace Webkul\SocialLogin\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository;

class LoginController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerSocialAccountRepository $customerSocialAccountRepository) {}

    /**
     * Redirects to the social provider
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        try {
            return Socialite::driver($provider)->redirect('shop.customers.account.profile.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->route('shop.customer.session.index');
        }
    }

    /**
     * Handles callback
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        
    //    dd($provider);
        // try {
            // $user = Socialite::driver('facebook')->user();

            $user = Socialite::driver($provider)->user();
            dd($user);
        // } catch (\Exception $e) {
        //     return redirect()->route('shop.customer.session.index');
        // }

        $customer = $this->customerSocialAccountRepository->findOrCreateCustomer($user, $provider);
        
        auth()->guard('customer')->login($customer, true);

        Event::dispatch('customer.after.login', $customer);

        return redirect()->intended(route('shop.customers.account.profile.index'));
    }

    public function BK1handleProviderCallback($provider)
    {
        
        // try {
        //     $user = Socialite::driver($provider)->user();
        // } catch (\Exception $e) {
        //     return redirect()->route('shop.customer.session.index');
        // }

        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('shop.customer.session.index');
        }

        $customer = $this->customerSocialAccountRepository->findOrCreateCustomer($user, $provider);

        auth()->guard('customer')->login($customer, true);

        // Event passed to prepare cart after login
        Event::dispatch('customer.after.login', $customer->email);

        return redirect()->intended('shop.customers.account.profile.index');
    }

    public function BKHitenhandleProviderCallback($provider)
    {
        
        
       // dd($user);
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Failed to login using.'.$e->getMessage());
        }

        $customer = $this->customerSocialAccountRepository->findOrCreateCustomer($user, $provider);

        auth()->guard('customer')->login($customer, true);

        // Event passed to prepare cart after login
        Event::dispatch('customer.after.login', $customer->email);

        return redirect()->intended('shop.customers.account.profile.index');
    }


}



// New 
// App ID 3746821275577322
// App secret 424dc0a033a4b070c487c86705cd56bd