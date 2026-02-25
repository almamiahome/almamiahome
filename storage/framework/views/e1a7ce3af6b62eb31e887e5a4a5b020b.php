<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use function Laravel\Folio\{middleware, name};
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Devdojo\Auth\Traits\HasConfigs;

if(!isset($_GET['preview']) || (isset($_GET['preview']) && $_GET['preview'] != true) || !app()->isLocal()){
    middleware(['guest']);
}

name('auth.login');

new class extends Component
{
    use HasConfigs;

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    #[Validate('bool')]
    public $rememberMe = false;

    public $showPasswordField = false;

    public $showIdentifierInput = true;
    public $showSocialProviderInfo = false;

    public $language = [];

    public $twoFactorEnabled = true;

    public $userSocialProviders = [];

    public $userModel = null;

    public function mount(){
        $this->loadConfigs();
        $this->twoFactorEnabled = $this->settings->enable_2fa;
        $this->userModel = app(config('auth.providers.users.model'));
    }

    public function editIdentity(){
        if($this->showPasswordField){
            $this->showPasswordField = false;
            return;
        }

        $this->showIdentifierInput = true;
        $this->showSocialProviderInfo = false;
    }

    public function authenticate()
    {

        if(!$this->showPasswordField){
            $this->validateOnly('email');
            $userTryingToValidate = $this->userModel->where('email', $this->email)->first();
            if(!is_null($userTryingToValidate)){
                if(is_null($userTryingToValidate->password)){
                    $this->userSocialProviders = [];
                    // User is attempting to login and password is null. Need to show Social Provider info
                    foreach($userTryingToValidate->socialProviders->all() as $provider){
                        array_push($this->userSocialProviders, $provider->provider_slug);
                    }
                    $this->showIdentifierInput = false;
                    $this->showSocialProviderInfo = true;
                    return;
                }
            }

            // Check if account exists before login and handle error if user is not found
            if(config('devdojo.auth.settings.check_account_exists_before_login') && is_null($userTryingToValidate)){
                $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-email', {})); }, 10);");
                $this->addError('email', trans(config('devdojo.auth.language.login.couldnt_find_your_account')));
                return;
            }

            $this->showPasswordField = true;
            $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-password', {})); }, 10);");
            return;
        }


        $this->validate();

        $credentials = ['email' => $this->email, 'password' => $this->password];
        
        // Fire Attempting event manually
        event(new Attempting('web', $credentials, false));
        
        if(!\Auth::validate($credentials)){
            // Fire Failed event manually
            event(new Failed('web', null, $credentials)); 
            $this->addError('password', trans('auth.failed'));
            return;
        }

        $userAttemptingLogin = $this->userModel->where('email', $this->email)->first();

        if(!isset($userAttemptingLogin->id)){
            $this->addError('password', trans('auth.failed'));
            return;
        }

        if($this->twoFactorEnabled && !is_null($userAttemptingLogin->two_factor_confirmed_at)){
            // We want this user to login via 2fa
            session()->put([
                'login.id' => $userAttemptingLogin->getKey()
            ]);

            return redirect()->route('auth.two-factor-challenge');

        } else {
            if (!Auth::attempt($credentials, $this->rememberMe)) {
                event(new Failed('web', null, $credentials)); // Fire Failed Attempt
                $this->addError('password', trans('auth.failed'));
                return;
            }

            event(new Login(auth()->guard('web'), $this->userModel->where('email', $this->email)->first(), true));

            if(session()->get('url.intended') != route('logout.get')){
                session()->regenerate();
                redirect()->intended(config('devdojo.auth.settings.redirect_after_auth'));
            } else {
                session()->regenerate();
                return redirect(config('devdojo.auth.settings.redirect_after_auth'));
            }
        }

    }
};

?>

<?php if (isset($component)) { $__componentOriginal67ed6d3aaccf7789a5a85f930ccb5eb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal67ed6d3aaccf7789a5a85f930ccb5eb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.layouts.app','data' => ['title' => ''.e(config('devdojo.auth.language.login.page_title')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e(config('devdojo.auth.language.login.page_title')).'']); ?>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiYXV0aC5sb2dpbiIsInBhdGgiOiJ2ZW5kb3JcL2RldmRvam9cL2F1dGhcL3Jlc291cmNlc1wvdmlld3NcL3BhZ2VzXC9hdXRoXC9sb2dpbi5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-2655152076-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal67ed6d3aaccf7789a5a85f930ccb5eb5)): ?>
<?php $attributes = $__attributesOriginal67ed6d3aaccf7789a5a85f930ccb5eb5; ?>
<?php unset($__attributesOriginal67ed6d3aaccf7789a5a85f930ccb5eb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal67ed6d3aaccf7789a5a85f930ccb5eb5)): ?>
<?php $component = $__componentOriginal67ed6d3aaccf7789a5a85f930ccb5eb5; ?>
<?php unset($__componentOriginal67ed6d3aaccf7789a5a85f930ccb5eb5); ?>
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/resources/views/pages/auth/login.blade.php ENDPATH**/ ?>