# Contact Form plugin for Craft

This plugin allows you to add an email contact form to your website.


## Installation

To install Contact Form, follow these steps:

1.  Upload the contactform/ folder to your craft/plugins/ folder.
2.  Go to Settings > Plugins from your Craft control panel and enable the Contact Form plugin.
3.  Click on “Contact Form” to go to the plugin’s settings page, and configure the plugin how you’d like.

## Usage

Your contact form template can look something like this:

```jinja
 {% if errors is defined %}
    <ul class="errors">
        {% for field in errors %}
            {% for error in items %}
                <li>{{ error }}</li>
            {% endfor %}
        {% endfor %}
    </ul>
{% endif %}

<form method="post" action="" accept-charset="UTF-8">
    {{ getCsrfInput() }}
    <input type="hidden" name="action" value="contactForm/message/add">
    <input type="hidden" name="formId" value="1">
    <input type="hidden" name="redirect" value="contact/thanks">

    <h3><label for="name">Your Name</label></h3>
    <input id="name" type="text" name="name" value="{% if message is defined %}{{ message.name }}{% endif %}">

    <h3><label for="email">Your Email</label></h3>
    <input id="email" type="text" name="email" value="{% if message is defined %}{{ message.email }}{% endif %}">

    <h3><label for="message">Message</label></h3>
    <textarea rows="10" cols="40" id="message" name="message">{% if message is defined %}{{ message.message }}{% endif %}</textarea>

    <input type="submit" value="Send">
</form>
```

The only required fields are “email” and “message”. Everything else is optional.

### Redirecting after submit

If you have a ‘redirect’ hidden input, the user will get redirected to it upon successfully sending the email. The following variables can be used within the URL/path you set:

- `{name}`
- `{email}`

For example, if you wanted to redirect to a “contact/thanks” page and pass the sender’s name to it, you could set the input like this:

    <input type="hidden" name="redirect" value="contact/thanks?from={fromName}">

On your contact/thanks.html template, you can access that ‘from’ parameter using [craft.request.getQuery()](http://buildwithcraft.com/docs/templating/craft.request#getQuery):

```jinja
<p>Thanks for sending that in, {{ craft.request.getQuery('from') }}!</p>
```

Note that if you don’t include a ‘redirect’ input, the current page will get reloaded.


### Adding additional fields

You can add additional fields to your form by splitting your “message” field into multiple fields, using an array syntax for the input names:

```jinja
<h3><label for="message">Message</label></h3>
<textarea rows="10" cols="40" id="message" name="message[body]">{% if message is defined %}{{ message.message }}{% endif %}</textarea>

<h3><label for="phone">Your phone number</label></h3>
<input id="phone" type="text" name="message[Phone]" value="">

<h3>What services are you interested in?</h3>
<label><input type="checkbox" name="message[Services][]" value="Design"> Design</label>
<label><input type="checkbox" name="message[Services][]" value="Development"> Development</label>
<label><input type="checkbox" name="message[Services][]" value="Strategy"> Strategy</label>
<label><input type="checkbox" name="message[Services][]" value="Marketing"> Marketing</label>
```

If you have a primary “Message” field, you should name it ``message[body]``, like in that example.

An email sent with the above form might result in the following message:

    Phone: (555) 123-4567

    Services: Design, Development

    Hey guys, I really loved this simple contact form (I'm so tired of agencies
    asking for everything but my social security number up front), so I trust
    you guys know a thing or two about usability.

    I run a small coffee shop and we want to start attracting more freelancer-
    types to spend their days working from our shop (and sipping fine coffee!).
    A clean new website with lots of social media integration would probably
    help us out quite a bit there. Can you help us with that?

    Hope to hear from you soon.

    Cathy Chino

### The “Honeypot” field
The [Honeypot Captcha][honeypot] is a simple anti-spam technique, which greatly reduces the efficacy of spambots without expecting your visitors to decipher various tortured letterforms.

[honeypot]: http://haacked.com/archive/2007/09/11/honeypot-captcha.aspx/ "The origins of the Honeypot Captcha"

In brief, it works like this:

1. You add a normal text field (our “honeypot”) to your form, and hide it using CSS.
2. Normal (human) visitors won't fill out this invisible text field, but those crazy spambots will.
3. The ContactForm plugin checks to see if the “honeypot” form field contains text. If it does, it assumes the form was submitted by “Evil People”, and ignores it (but pretends that everything is A-OK, so the evildoer is none the wiser).

### Example “Honeypot” implementation
When naming your form field, it's probably best to avoid monikers such as “dieEvilSpammers”, in favour of something a little more tempting. For example:

```html
<input id="preferredKitten" name="preferredKitten" type="text">
```

In this case, you could hide your form field using the following CSS:

```css
input#preferredKitten { display: none; }
```

### File attachments

If you would like your contact form to accept file attachments, follow these steps:

1. Go to Settings > Plugins > Contact Form in your CP and make sure the plugin is set to allow attachments.
2. Make sure your opening HTML `<form>` tag contains `enctype="multipart/form-data"`.
3. Add a `<input type="file" name="attachment">` to your form.
4. If you want to allow multiple file attachments, use multiple `<input type="file" name="attachment[]">` inputs.


### Ajax form submissions

You can optionally post contact form submissions over Ajax if you’d like. Just send a POST request to your site with all of the same data that would normally be sent:

```js
$('#my-form').submit(function(ev) {
    // Prevent the form from actually submitting
    ev.preventDefault();

    // Get the post data
    var data = $(this).serialize();

    // Send it to the server
    $.post('/', data, function(response) {
        if (response.success) {
            $('#thanks').fadeIn();
        } else {
            // response.error will be an object containing any validation errors that occurred, indexed by field name
            // e.g. response.error.fromName => ['From Name is required']
            alert('An error occurred. Please try again.');
        }
    });
});
```

If using getCrsfInput() make sure you add these after the closing form tag.
```js
<script>
window.csrfTokenName = "{{ craft.config.csrfTokenName|e('js') }}";
window.csrfTokenValue = "{{ craft.request.csrfToken|e('js') }}";
</script>
```

## Changelog

### 2.0.3

* Added New option on Settings to add Name and Email to Body of the email message
* Added new option to modify the from address by default uses no-reply@{domain} email

### 2.0.2

* Better Error handling on the template part
* Saving Attachment names to the database and displaying them on the single entry view of the CP

### 2.0.1

* Auto Created Contact Form and fixed entry count on Admin

### 2.0.0

* Major Overhaul of the Plugin Fields and creating multiple Forms and grouping entries by form on the admin.

### 1.0.0

* Initial release
