Honeypot spam prevention for Laravel applications
=========

## How does it work? 

"Honeypot" method of spam prevention is a simple and effective way to defer some of the spam bots that come to your site. This technique is based on creating an input field that should be left empty by the real users of the application but will most likely be filled out by spam bots. 

This package creates a hidden DIV with two fields in it, honeypot field (like "my_name") and a honeytime field - an encrypted timestamp that marks the moment when the page was served to the user. When the form containing these inputs invisible to the user is submitted to your application, a custom validator that comes with the package checks that the honeypot field is empty and also checks the time it took for the user to fill out the form. If the form was filled out too quickly (i.e. less than 5 seconds) or if there was a value put in the honeypot field, this submission is most likely from a spam bot.

## Installation:

In your terminal type : `composer require msurguy/honeypot`. Or open up composer.json and add the following line under "require":

    {
        "require": {
            "msurguy/honeypot": "^1.0"
        }
    }

Next, add this line to 'providers' section of the app config file in `app/config/app.php`:

    'Msurguy\Honeypot\HoneypotServiceProvider',

Add the honeypot facade:

    'Honeypot' => 'Msurguy\Honeypot\HoneypotFacade'

At this point the package is installed and you can use it as follows.

## Usage :

Add the honeypot catcher to your form by inserting `Honeypot::generate(..)` like this: 

Laravel 5 & above:

    {!! Form::open('contact') !!}
        ...
        {!! Honeypot::generate('my_name', 'my_time') !!}
        ...
    {!! Form::close() !!}
    
Other Laravel versions:

    {{ Form::open('contact') }}
        ...
        {{ Honeypot::generate('my_name', 'my_time') }}
        ...
    {{ Form::close() }}

The `generate` method will output the following HTML markup (`my_time` field will contain an encrypted timestamp):
    
    <div id="my_name_wrap" style="display:none;">
        <input name="my_name" type="text" value="" id="my_name">
        <input name="my_time" type="text" value="eyJpdiI6IkxoeWhKc3prN2puZllEajRwZ3lrc0I5bU42bUFWbzF1NEVVOEhxbG9WcFE9IiwidmFsdWUiOiJxNEtBT0NpYW5lUjJvWXp6VE45a1U0V3dNbk9Jd2RUNW42NFpiQWtTRllRPSIsIm1hYyI6IjAyMWQ0NWI1NTVkYTBjZTAxMTdhZmJmNTY0ZDI4Nzg4NzU3ODU4MjM1Y2MxNTVkYjAwNmFhNzBmNTdlNmJmMjkifQ==">
    </div>

After adding the honeypot fields in the markup with the specified macro add the validation for the honeypot and honeytime fields of the form: 

    $rules = array(
        'email'     => "required|email",
        ...
        'my_name'   => 'honeypot',
        'my_time'   => 'required|honeytime:5'
    );

    $validator = Validator::make(Input::get(), $rules);

Please note that "honeytime" takes a parameter specifying number of seconds it should take for the user to fill out the form. If it takes less time than that the form is considered a spam submission.

That's it! Enjoy getting less spam in your inbox. If you need stronger spam protection, consider using [Akismet](https://github.com/kenmoini/akismet) or [reCaptcha](https://github.com/dontspamagain/recaptcha)   

## Testing

If you want to test the submission of a form using this package, you might want to disable Honeypot so that the validation passes. To do so, simply call the `disable()` method in your test:

    Honeypot::disable();

    $this->visit('contact')
        ->type('User', 'name')
        ->type('user@email.com', 'email')
        ->type('Hello World', 'message')
        ->press('submit')
        ->see('Your message has been sent!');

## Credits

Based on work originally created by Ian Landsman: <https://github.com/ianlandsman/Honeypot>

## License

This work is MIT-licensed by Maksim Surguy.
