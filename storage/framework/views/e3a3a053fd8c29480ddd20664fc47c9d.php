<?php

use Devdojo\Auth\Models\SocialProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Devdojo\Auth\Helper;
use Devdojo\Auth\Traits\HasConfigs;
use function Laravel\Folio\{middleware, name};

if (!isset($_GET['preview']) || (isset($_GET['preview']) && $_GET['preview'] != true) || !app()->isLocal()) {
    middleware(['guest']);
}

name('auth.register');

new class extends Component
{
    use HasConfigs;

    public $name;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public $showNameField = false;
    public $showEmailField = true;
    public $showPasswordField = false;
    public $showPasswordConfirmationField = false;
    public $showEmailRegistration = true;

    public function rules()
    {
        if (!$this->settings->enable_email_registration) {
            return [];
        }

        $nameValidationRules = [];
        if (config('devdojo.auth.settings.registration_include_name_field')) {
            $nameValidationRules = ['name' => 'required'];
        }

        $passwordValidationRules = ['password' => 'required|min:8'];
        if (config('devdojo.auth.settings.registration_include_password_confirmation_field')) {
            $passwordValidationRules['password'] .= '|confirmed';
        }
        return array_merge(
            $nameValidationRules,
            ['email' => 'required|email|unique:users'],
            $passwordValidationRules,
        );
    }

    public function mount()
    {
        $this->loadConfigs();

        if (!$this->settings->registration_enabled) {
            session()->flash('error', config('devdojo.auth.language.register.registrations_disabled', 'Registrations are currently disabled.'));
            redirect()->route('auth.login');
            return;
        }

        if (!$this->settings->enable_email_registration) {
            $this->showEmailRegistration = false;
            $this->showNameField = false;
            $this->showEmailField = false;
            $this->showPasswordField = false;
            $this->showPasswordConfirmationField = false;
            return;
        }

        if ($this->settings->registration_include_name_field) {
            $this->showNameField = true;
        }

        if ($this->settings->registration_show_password_same_screen) {
            $this->showPasswordField = true;

            if ($this->settings->registration_include_password_confirmation_field) {
                $this->showPasswordConfirmationField = true;
            }
        }
    }

    public function register()
    {
        if (!$this->settings->registration_enabled) {
            session()->flash('error', config('devdojo.auth.language.register.registrations_disabled', 'Registrations are currently disabled.'));
            return redirect()->route('auth.login');
        }

        if (!$this->settings->enable_email_registration) {
            session()->flash('error', config('devdojo.auth.language.register.email_registration_disabled', 'Email registration is currently disabled. Please use social login.'));
            return redirect()->route('auth.register');
        }

        if (!$this->showPasswordField) {
            if ($this->settings->registration_include_name_field) {
                $this->validateOnly('name');
            }
            $this->validateOnly('email');

            $this->showPasswordField = true;
            if ($this->settings->registration_include_password_confirmation_field) {
                $this->showPasswordConfirmationField = true;
            }
            $this->showNameField = false;
            $this->showEmailField = false;
            $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-password', {})); }, 10);");
            return;
        }

        $this->validate();

        $userData = [
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];

        if ($this->settings->registration_include_name_field) {
            $userData['name'] = $this->name;
        }

        $user = app(config('auth.providers.users.model'))->create($userData);

        event(new Registered($user));

        Auth::login($user, true);

        if (config('devdojo.auth.settings.registration_require_email_verification')) {
            return redirect()->route('verification.notice');
        }

        if (session()->get('url.intended') != route('logout.get')) {
            session()->regenerate();
            redirect()->intended(config('devdojo.auth.settings.redirect_after_auth'));
        } else {
            session()->regenerate();
            return redirect(config('devdojo.auth.settings.redirect_after_auth'));
        }
    }
};

?>

<?php if (isset($component)) { $__componentOriginal67ed6d3aaccf7789a5a85f930ccb5eb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal67ed6d3aaccf7789a5a85f930ccb5eb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.layouts.app','data' => ['title' => ''.e(config('devdojo.auth.language.register.page_title')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e(config('devdojo.auth.language.register.page_title')).'']); ?>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiYXV0aC5yZWdpc3RlciIsInBhdGgiOiJ2ZW5kb3JcL2RldmRvam9cL2F1dGhcL3Jlc291cmNlc1wvdmlld3NcL3BhZ2VzXC9hdXRoXC9yZWdpc3Rlci5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-3922714348-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/resources/views/pages/auth/register.blade.php ENDPATH**/ ?>